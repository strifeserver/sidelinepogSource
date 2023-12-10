<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Fight;
use App\Models\Setting;
use App\Models\Bet as PlayerBet;

use Auth;
use Session;

use App\Events\Bet;
use App\Events\ChangeTeam;
use App\Events\EventStatus;
use App\Events\DeclareWinner;
use App\Events\JumpFight;
use App\Events\SendNotification;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\Wallet;

use App\Events\PlaceBet;

class EventsController extends Controller
{
    public function createEvent(Request $request)
    {

        if (Auth::user()->type == 'super-admin') {

        } else if (Auth::user()->type != 'declarator' && Auth::user()->type != 'super-admin') {
            return redirect()->route('dashboard');
        }
        $request['created_by'] = Auth::id();
        $event = Event::create($request->all());

        $fight = new Fight();
        $fight->event_id = $event->id;
        $fight->fight_number = 1;
        $fight->save();

        $event = Event::find($event->id);
        $event->active_fight = $fight->id;
        $event->save();

        $request->session()->flash('success', 'Event created successfully!');
        return redirect()->route('events');
    }

    public function updateEvent(Request $request, $id){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        Event::find($id)->update($request->all());
        $request->session()->flash('success', 'Event updated successfully!');
        return back();
    }

    public function deleteEvent($id){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }
        Event::find($id)->delete();
        Session::flash('success', 'Event updated successfully!');
        return back();
    }


    public function startEvent(Request $request, $id){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $event = Event::find($id);
        $event->status = "open";
        $event->save();

        $fight = Fight::find($event->active_fight);

        event(new EventStatus("open",$fight->id,$fight->fight_number,$event->event_id));
        $request->session()->put('fight_number', $fight->fight_number);
        Session::flash('success', 'Event started successfully!');
        return back();
    }

    public function endEvent($id){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $event = Event::find($id);
        $event->status = "completed";
        $event->save();
        Session::flash('success', 'Event has been marked completed!');
        return back();
    }

    public function changeFightStatus(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = Fight::find($request['id']);
        $fight->status = strtolower($request['status']);
        $fight->save();

        $event = Event::find($fight->event_id);
        $deduction = Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $deduction->value;
        $betMeron = PlayerBet::where('fight_id',$request['id'])->where('bet','meron')->sum('amount');
        $betWala = PlayerBet::where('fight_id',$request['id'])->where('bet','wala')->sum('amount');
        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);

        $percentMeron = number_format($odds['meron'],2,'.','');
        $percentWala = number_format($odds['wala'],2,'.','');

        event(new Bet(strtoupper($request['status']),$event->event_id,$percentMeron,$percentWala));
        return response()->json([
            'status' => $request['status'],
            'fight_id' => $fight->id,
            'fight_number' => $fight->fight_number,
        ]);
    }


    public function updateTeams(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = Fight::find($request['fight_id']);
        $fight->team_1 = strtoupper($request['team_1']);
        $fight->team_2 = strtoupper($request['team_2']);
        $fight->save();

        $data = [
            'team_1' => $fight->team_1,
            'team_2' => $fight->team_2
        ];

        $event = Event::find($fight->event_id);
        event(new ChangeTeam($data,$event->event_id));
        return response()->json($data);
    }


    public function changeFightNumber(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = Fight::find($request['id']);
        $fight->fight_number = $request['fight_number'];
        $fight->save();

        $event = Event::find($fight->event_id);

        event(new JumpFight($fight->fight_number,$event->event_id));
        return response()->json([
            'msg' => "Fight number updated!",
            'fight_number' => $fight->fight_number
        ],200);
    }

    public function placeGhostBet(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = Fight::find($request['fight_id']);
        $fight->ghost_bet_wala = $fight->ghost_bet_wala+$request['amount'];
        $fight->ghost_bet_meron = $fight->ghost_bet_meron+$request['amount'];
        $fight->save();
    }

    public function redeclareWinner(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = Fight::find($request['fight_id']);
        $bets = PlayerBet::where('fight_id',$fight->id)->where('result',$fight->result)->get();
        $event = Event::find($request['event_id']);
        $game = Game::find($event->game_id);
        $deduction = Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $deduction->value;
        $betMeron = PlayerBet::where('fight_id',$request['fight_id'])->where('bet','meron')->sum('amount');
        $betWala = PlayerBet::where('fight_id',$request['fight_id'])->where('bet','wala')->sum('amount');
        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
        $percentMeron = number_format($odds['meron'],2,'.','');
        $percentWala = number_format($odds['wala'],2,'.','');

        $laban = $fight->fight_number;


        foreach($bets as $b){
            //bawi panalo ng lahat ng nanalo
            $wallet = Wallet::where('user_id', $b->user_id)->first();
            $wallet->balance = $wallet->balance - $b->amount_won;
            $wallet->save();

            PlayerBet::find($b->id)->update(['amount_won'=>0]);

            $tx = new Transaction();
            $tx->user_id = $wallet->user_id;
            $tx->bet_id = $b->id;
            $tx->amount = $b->amount_won;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'bet-'.$request['result'];
            $tx->direction = 'out';
            $tx->remarks = 'adjustment - redeclare for fight '.$laban. ' ' .$request['result'].'wins';
            $tx->save();


            // if($b->result != $request['result']){
            //     $wallet = Wallet::where('user_id', $b->user_id)->first();
            //     $wallet->balance = $wallet->balance - $b->amount_won;
            //     $wallet->save();

            //     PlayerBet::find($b->id)->update(['amount_won'=>0]);

            //     $tx = new Transaction();
            //     $tx->user_id = $wallet->user_id;
            //     $tx->bet_id = $b->id;
            //     $tx->amount = $b->amount_won;
            //     $tx->ending_balance = $wallet->balance;
            //     $tx->type = 'bet-'.$request['result'];
            //     $tx->direction = 'out';
            //     $tx->remarks = 'adjustment - redeclare fight '.$request['result'].' wins';
            //     $tx->save();
            // }


            // if($b->result != null){
            //     $wallet = Wallet::where('user_id',1)->first();
            //     $wallet->commission = $wallet->commission-$b->plasada;
            //     $wallet->save();

            //     $tx = new Transaction();
            //     $tx->user_id = 1;
            //     $tx->bet_id = $b->id;
            //     $tx->amount = $b->plasada;
            //     $tx->ending_balance = $wallet->commission;
            //     $tx->type = 'plasada-out';
            //     $tx->direction = 'out';
            //     $tx->remarks = $laban. ' plasada deducted for redeclare '. $request['result'];
            //     $tx->save();


            //     if($fight->op_id != null){
            //         $wallet = Wallet::where('user_id',$fight->op_id)->first();
            //         $wallet->commission = $wallet->commission-$fight->operator_commission;
            //         $wallet->save();

            //         $tx = new Transaction();
            //         $tx->user_id = $fight->op_id;
            //         $tx->bet_id = $fight->id;
            //         $tx->amount = $fight->operator_commission;
            //         $tx->ending_balance = $wallet->commission;
            //         $tx->type = 'commission-out';
            //         $tx->direction = 'out';
            //         $tx->remarks = $laban.' commission deducted for redeclare '. $request['result'];
            //         $tx->save();
            //     }

            //     if($fight->subop_id != null){
            //         $wallet = Wallet::where('user_id',$fight->subop_id)->first();
            //         $wallet->commission = $wallet->commission-$fight->sub_operator_commission;
            //         $wallet->save();
            //         $tx = new Transaction();
            //         $tx->user_id = $fight->subop_id;
            //         $tx->bet_id = $fight->id;
            //         $tx->amount = $fight->sub_operator_commission;
            //         $tx->ending_balance = $wallet->commission;
            //         $tx->type = 'commission-out';
            //         $tx->direction = 'out';
            //         $tx->remarks = $laban.' commission deducted for redeclare '. $request['result'];
            //         $tx->save();
            //     }

            //     if($fight->ma_id != null){
            //         $wallet = Wallet::where('user_id',$fight->ma_id)->first();
            //         $wallet->commission = $wallet->commission-$fight->master_agent_commission;
            //         $wallet->save();
            //         $tx = new Transaction();
            //         $tx->user_id = $fight->ma_id;
            //         $tx->bet_id = $fight->id;
            //         $tx->amount = $fight->master_agent_commission;
            //         $tx->ending_balance = $wallet->commission;
            //         $tx->type = 'commission-out';
            //         $tx->direction = 'out';
            //         $tx->remarks = $laban.' commission deducted for redeclare '. $request['result'];
            //         $tx->save();
            //     }


            //     if($fight->agent_id != null){
            //         $wallet = Wallet::where('user_id',$fight->agent_id)->first();
            //         $wallet->commission = $wallet->commission-$fight->gold_agent_commission;
            //         $wallet->save();
            //         $tx = new Transaction();
            //         $tx->user_id = $fight->agent_id;
            //         $tx->bet_id = $fight->id;
            //         $tx->amount = $fight->gold_agent_commission;
            //         $tx->ending_balance = $wallet->commission;
            //         $tx->type = 'commission-out';
            //         $tx->direction = 'out';
            //         $tx->remarks = $laban.' commission deducted for redeclare '. $request['result'];
            //         $tx->save();
            //     }

            //     if($fight->silver_id != null){
            //         $wallet = Wallet::where('user_id',$fight->silver_id)->first();
            //         $wallet->commission = $wallet->commission-$fight->silver_agent_commission;
            //         $wallet->save();
            //         $tx = new Transaction();
            //         $tx->user_id = $fight->silver_id;
            //         $tx->bet_id = $fight->id;
            //         $tx->amount = $fight->silver_agent_commission;
            //         $tx->ending_balance = $wallet->commission;
            //         $tx->type = 'commission-out';
            //         $tx->direction = 'out';
            //         $tx->remarks = $laban.' commission deducted for redeclare '. $request['result'];
            //         $tx->save();
            //     }
            // }
        }

        //update fight new data
        $fight->result = $request['result'];
        $fight->status = 'finished';
        $fight->save();


        $bets = PlayerBet::where('fight_id',$fight->id)->where('result',$request['result'])->get();
        PlayerBet::where('fight_id',$fight->id)->update(['result'=>$request['result']]);
        $fightBets = PlayerBet::where('fight_id',$fight->id)->where('result',$request['result'])->get();

        if($request['result'] == 'meron' || $request['result'] == 'wala'){
            foreach($fightBets as $fight){
                // if($fight->amount_won == 0 && $fight->result == $request['result']){

                // }

                //add plasada to superadmin commission
                $wallet = Wallet::where('user_id',1)->first();
                $wallet->commission = $wallet->commission+$fight->plasada;
                $wallet->save();

                $tx = new Transaction();
                $tx->user_id = 1;
                $tx->bet_id = $fight->id;
                $tx->amount = $fight->plasada;
                $tx->ending_balance = $wallet->commission;
                $tx->type = 'plasada-in';
                $tx->direction = 'in';
                $tx->remarks = 'plasada from player bet '.$laban;
                $tx->save();


                if($fight->op_id != null){
                    $wallet = Wallet::where('user_id',$fight->op_id)->first();
                    $wallet->commission = $wallet->commission+$fight->operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->op_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet '.$laban;
                    $tx->save();
                }


                if($fight->subop_id != null){
                    $wallet = Wallet::where('user_id',$fight->subop_id)->first();
                    $wallet->commission = $wallet->commission+$fight->sub_operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->subop_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->sub_operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet '.$laban;
                    $tx->save();
                }


                if($fight->ma_id != null){
                    $wallet = Wallet::where('user_id',$fight->ma_id)->first();
                    $wallet->commission = $wallet->commission+$fight->master_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->ma_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->master_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet '.$laban;
                    $tx->save();
                }


                if($fight->agent_id != null){
                    $wallet = Wallet::where('user_id',$fight->agent_id)->first();
                    $wallet->commission = $wallet->commission+$fight->gold_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->agent_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->gold_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet '.$laban;
                    $tx->save();
                }

                if($fight->silver_id != null){
                    $wallet = Wallet::where('user_id',$fight->silver_id)->first();
                    $wallet->commission = $wallet->commission+$fight->silver_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->silver_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->silver_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet '.$laban;
                    $tx->save();
                }
            }
        }

        if($request['result'] == 'meron'){
            $this->meronWalaWinnings($request['result'],$request['fight_id'],$percentMeron,true);
        }

        if($request['result'] == 'wala'){
            $this->meronWalaWinnings($request['result'],$request['fight_id'],$percentWala,true);
        }

        if($request['result'] == 'draw'){
            $this->drawWinnings($request['fight_id'],$playerDeduction);
        }

        if($request['result'] == 'cancelled'){
            $this->cancelFight($request['fight_id']);
        }

        $wins = \App\Models\Fight::where('event_id',$request['event_id'])->where('status','finished')->select('result','fight_number')->get();
        return response()->json([
            'result' => removeUnderscore($request['result']),
            'status' => 'not_open',
            'fight_id' => $fight->id,
            'fight_number' => $fight->fight_number,
            'wins' => $wins
        ]);
    }

    public function declareWinner(Request $request){
        if(Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $event = Event::find($request['event_id']);
        $game = Game::find($event->game_id);
        $deduction = Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $deduction->value;

        $fight = Fight::find($request['id']);
        $fight->status = 'finished';
        $fight->result = $request['result'];
        $fight->save();

        $nextFighNum = $fight->fight_number+1;
        $findExistingOpenFight = Fight::where('event_id',$request['event_id'])->where('fight_number',$nextFighNum)->where('status','!=','finished')->first();

        if(!$findExistingOpenFight){
            //create new Fight
            $newFight = new Fight();
            $newFight->event_id = $request['event_id'];
            $newFight->fight_number = $nextFighNum;
            $newFight->status = 'not_open';
            $newFight->team_1 =  $fight->team_1;
            $newFight->team_2 =  $fight->team_2;
            $newFight->status = 'not_open';
            $newFight->save();

             //update current fight in event
            $event = Event::find($request['event_id']);
            $event->active_fight = $newFight->id;
            $event->save();
        }


        $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();

        $declareData = [
            'result' => removeUnderscore($request['result']),
            'status' => 'not_open',
            'fight_id' => $newFight->id,
            'team_1' => $newFight->team_1,
            'team_2' => $newFight->team_2,
            'fight_number' => $newFight->fight_number,
            'wins' => $wins
        ];

        //insert code here to give winnings to users

        $betMeron = PlayerBet::where('fight_id',$request['id'])->where('bet','meron')->sum('amount');
        $betWala = PlayerBet::where('fight_id',$request['id'])->where('bet','wala')->sum('amount');

        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
        $percentMeron = $odds['meron'];
        $percentWala = $odds['wala'];

        PlayerBet::where('fight_id',$request['id'])->update(['result'=>$request['result']]);

        //insert code here to calculate total agent commission in this fight
        $fightBets = PlayerBet::where('fight_id',$request['id'])->get();

        if($request['result'] == 'meron' || $request['result'] == 'wala'){

            foreach($fightBets as $fight){
                //add plasada to superadmin commission
                $wallet = Wallet::where('user_id',1)->first();
                $wallet->commission = $wallet->commission+$fight->plasada;
                $wallet->save();

                $tx = new Transaction();
                $tx->user_id = 1;
                $tx->bet_id = $fight->id;
                $tx->amount = $fight->plasada;
                $tx->ending_balance = $wallet->commission;
                $tx->type = 'plasada-in';
                $tx->direction = 'in';
                $tx->remarks = 'plasada from player bet';
                $tx->save();

                //credit commissions to operator
                if($fight->op_id != null){
                    $wallet = Wallet::where('user_id',$fight->op_id)->first();
                    $wallet->commission = $wallet->commission+$fight->operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->op_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to sub operator
                if($fight->subop_id != null){
                    $wallet = Wallet::where('user_id',$fight->subop_id)->first();
                    $wallet->commission = $wallet->commission+$fight->sub_operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->subop_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->sub_operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to master agent
                if($fight->ma_id != null){
                    $wallet = Wallet::where('user_id',$fight->ma_id)->first();
                    $wallet->commission = $wallet->commission+$fight->master_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->ma_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->master_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to fold agent
                if($fight->agent_id != null){
                    $wallet = Wallet::where('user_id',$fight->agent_id)->first();
                    $wallet->commission = $wallet->commission+$fight->gold_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->agent_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->gold_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                if($fight->silver_id != null){
                    $wallet = Wallet::where('user_id',$fight->silver_id)->first();
                    $wallet->commission = $wallet->commission+$fight->silver_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $fight->silver_id;
                    $tx->bet_id = $fight->id;
                    $tx->amount = $fight->silver_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

            }
        }


        if($request['result'] == 'meron'){
            $this->meronWalaWinnings($request['result'],$request['id'],$percentMeron,false);
        }

        if($request['result'] == 'wala'){
            $this->meronWalaWinnings($request['result'],$request['id'],$percentWala,false);
        }

        if($request['result'] == 'draw'){
            $this->drawWinnings($request['id']);
        }

        if($request['result'] == 'cancelled'){
            $this->cancelFight($request['id']);
        }

        event(new Bet("NOT OPEN",$event->event_id,$percentMeron,$percentWala));
        event(new DeclareWinner($declareData,$event->event_id));

        return response()->json([
            'result' => removeUnderscore($request['result']),
            'status' => 'not_open',
            'fight_id' => $newFight->id,
            'fight_number' => $newFight->fight_number,
            'wins' => $wins
        ]);
    }

    public function drawWinnings($fightId){
        $winningBets = PlayerBet::where('fight_id',$fightId)->get();
        $fight = Fight::find($fightId);
        $event = Event::find($fight->event_id);
        $dataReturn = [];
        $eventID = 0;
        foreach($winningBets as $bet){
            if($bet->bet == 'draw'){
                $totalWinnings = $bet->amount*8;
                PlayerBet::find($bet->id)->update(['amount_won'=>$totalWinnings]);
                //add winings to player;

                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$totalWinnings;
                $wallet->save();
                $playerId = $wallet->user_id;
                $playerBalance = $wallet->balance;

                // log winning transaction
                $tx = new Transaction();
                $tx->user_id = $bet->user_id;
                $tx->bet_id = $bet->id;
                $tx->amount = $totalWinnings;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'bet-draw';
                $tx->direction = 'in';
                $tx->remarks = 'win from draw';
                $tx->save();

                //deduct winnings from loader wallet
                $totalWinnings = $bet->amount*7;
                $wallet = Wallet::where('user_id',1)->first();
                $wallet->balance = $wallet->balance-bcdiv($totalWinnings,1,2);
                $wallet->save();

                //log wallet deduction - only for draw wins
                $tx = new Transaction();
                $tx->user_id = 1;
                $tx->bet_id = $bet->id;
                $tx->amount = bcdiv($totalWinnings,1,2);
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'bet-draw';
                $tx->direction = 'out';
                $tx->remarks = 'pay for draw winning';
                $tx->save();

                $dataReturn[] =[
                    'balance' => $playerBalance,
                    'user_id' => $playerId,
                    'percent' => 800,
                    'betAmount' => $bet->amount,
                    'result' => 'draw'
                ];

                $playerBet = PlayerBet::find($bet->id);
                $playerBet->amount_won = bcdiv($totalWinnings,1,2);
                // $playerBet->plasada = 0;
                // $playerBet->operator_commission = 0;
                // $playerBet->sub_operator_commission = 0;
                // $playerBet->master_agent_commission = 0;
                // $playerBet->gold_agent_commission = 0;
                // $playerBet->silver_agent_commission = 0;
                $playerBet->result = 'draw';
                $playerBet->save();

            }else{

                if($bet->bet == $bet->result){
                    $wallet = Wallet::where('user_id',$bet->user_id)->first();
                    $wallet->balance = $wallet->balance-$bet->amount_won;
                    $wallet->save();
                }

                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$bet->amount;
                $wallet->save();

                // log winning transaction
                $tx = new Transaction();
                $tx->user_id = $bet->user_id;
                $tx->bet_id = $bet->id;
                $tx->amount = $bet->amount;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'refund';
                $tx->direction = 'in';
                $tx->remarks = 'refund for draw fight';
                $tx->save();

                $dataReturn[] =[
                    'balance' => number_format($wallet->balance,2,'.',','),
                    'user_id' => $wallet->user_id,
                    'percent' => 0,
                    'betAmount' => 0,
                    'result' => null
                ];

                $playerBet = PlayerBet::find($bet->id);
                // $playerBet->amount_won = 0;
                // $playerBet->plasada = 0;
                // $playerBet->operator_commission = 0;
                // $playerBet->sub_operator_commission = 0;
                // $playerBet->master_agent_commission = 0;
                // $playerBet->gold_agent_commission = 0;
                // $playerBet->silver_agent_commission = 0;
                $playerBet->result = 'draw';
                $playerBet->save();
            }
        }

        event(new SendNotification($dataReturn,$event->event_id));
    }

    public function cancelFight($fightId){
        $winningBets = PlayerBet::where('fight_id',$fightId)->get();
        $fight = Fight::find($fightId);
        $event = Event::find($fight->event_id);
        $dataReturn = [];

            foreach($winningBets as $bet){
                $eventID = $bet->event_id;
                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$bet->amount;
                $wallet->save();

                $tx = new Transaction();
                $tx->user_id = $bet->user_id;
                $tx->bet_id = $bet->id;
                $tx->amount = $bet->amount;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'refund';
                $tx->direction = 'in';
                $tx->remarks = 'refund for cancelled fight';
                $tx->save();

                $playerBet = PlayerBet::find($bet->id);
                $playerBet->amount_won = 0;
                // $playerBet->plasada = 0;
                // $playerBet->operator_commission = 0;
                // $playerBet->sub_operator_commission = 0;
                // $playerBet->master_agent_commission = 0;
                // $playerBet->gold_agent_commission = 0;
                // $playerBet->silver_agent_commission = 0;
                $playerBet->result = 'cancelled';
                $playerBet->save();

                $dataReturn[] = [
                    'balance' => number_format($wallet->balance,2,'.',','),
                    'user_id' => $wallet->user_id,
                    'percent' => 0,
                    'betAmount' => 0,
                    'result' => 'cancelled'
                ];


            }

        event(new SendNotification($dataReturn,$event->event_id));
    }


    public function meronWalaWinnings($result,$fightId,$percentage,$redeclare){
        $winningBets = PlayerBet::where('fight_id',$fightId)->get();
        $fight = Fight::find($fightId);
        $event = Event::find($fight->event_id);

        $dataReturn = [];
        foreach($winningBets as $bet){
            if($bet->bet == 'draw'){
                //add draw bets from loader wallet
                $wallet = Wallet::where('user_id',1)->first();
                $wallet->balance = $wallet->balance+$bet->amount;
                $wallet->save();

                //log wallet addition - only for draw bets
                $tx = new Transaction();
                $tx->user_id = 1;
                $tx->bet_id = $bet->id;
                $tx->amount = $bet->amount;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'bet-draw';
                $tx->direction = 'in';
                $tx->remarks = 'profit from draw bets';
                $tx->save();
            }else{
                if($bet->bet == $result){

                    if($bet->amount_won == 0 || $redeclare == true){
                        $totalWinnings = calculateAmountPercent($percentage,$bet->amount);
                        PlayerBet::find($bet->id)->update(['amount_won'=>bcdiv($totalWinnings,1,2)]);

                        if($redeclare == true){
                            $totalWinnings = $totalWinnings;
                        }

                        $wallet = Wallet::where('user_id',$bet->user_id)->first();
                        $wallet->balance = $wallet->balance+bcdiv($totalWinnings,1,2);
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $bet->user_id;
                        $tx->bet_id = $bet->id;
                        $tx->amount = $bet->amount;
                        $tx->ending_balance = $wallet->balance;
                        $tx->type = 'bet-'.$bet->bet;
                        $tx->direction = 'in';
                        $tx->remarks = 'winnings from fight ('.$bet->bet.')';
                        if($redeclare == true){
                            $tx->remarks = 'incomplete winnings credited - bet amount returned';
                        }
                        $tx->save();

                        $dataReturn[] =[
                            'balance' => number_format($wallet->balance,2,'.',','),
                            'user_id' => $wallet->user_id,
                            'percent' => $percentage,
                            'betAmount' => $bet->amount,
                            'result' => $result
                        ];
                    }
                }
            }

        }

        if($redeclare == false){
            event(new SendNotification($dataReturn,$event->event_id));
        }

    }
}
