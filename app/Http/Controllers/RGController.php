<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Fight;
use App\Models\Event;
use App\Models\Game;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Bet;

use App\Events\PlaceBet;
use App\Events\AllBets;
use App\Events\DeclareWinner;
use App\Events\Bet as PlayerBet;
use App\Events\SendNotification;

use Auth;
use DB;

class RGController extends Controller
{
    public function placeRGBet(Request $request){
        $comm = Setting::where('setting_name','operator_commission')->first();
        $fight = Fight::find($request['fight_id']);
        $event = Event::find($fight->event_id);
        $game = Game::find($event->game_id);
        $request['user_id'] = Auth::id();

        $wallet = Wallet::where('user_id',Auth::id())->first();

        if($fight->status == 'closed' || $fight->status == 'finished'){
            return response()->json(['msg'=>'Unable to place bet. Betting closed!'],409);
        }

        if($wallet->balance < $request['amount']){
            return response()->json([
                'msg' => "Insufficient wallet balance to place bet!"
            ],403);
        }else{
            //deduct bet amount from wallet
            $wallet->balance = $wallet->balance-$request['amount'];
            $wallet->save();

            if(Auth::user()->type == 'player'){

                if(Auth::user()->referrer_type == 'operator'){
                    $operatorData = getUplineCommission(Auth::id());
                    $operatorComm = $request['amount']*($operatorData['commission']/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }


                if(Auth::user()->referrer_type == 'sub-operator'){

                    //direct agent
                    $subOperatorData = getUplineCommission(Auth::id());
                    $subOperatorComm = $request['amount']*($subOperatorData['commission']/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOperatorData['user_id'];

                    //upline agent
                    $operatorData =  getUplineCommission(Auth::user()->referred_by);
                    $opPercent = $operatorData['commission']-$subOperatorData['commission'];
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                if(Auth::user()->referrer_type == 'master-agent'){
                    //direct agent
                    $masterAgentData = getUplineCommission(Auth::id());
                    $MAComm = $request['amount']*($masterAgentData['commission']/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];

                }

                if(Auth::user()->referrer_type == 'gold-agent'){
                    //direct agent
                    $goldAgentData = getUplineCommission(Auth::id());
                    $goldComm = $request['amount']*($goldAgentData['commission']/100);
                    $request['gold_agent_commission'] = $goldComm;
                    $request['agent_id'] = $goldAgentData['user_id'];

                    //upline agent - master-agent
                    $masterAgentData = getUplineCommission($goldAgentData['user_id']);
                    $MAPercent = $masterAgentData['commission'] - $goldAgentData['commission'];
                    $MAComm = $request['amount']*($MAPercent/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                if(Auth::user()->referrer_type == 'silver-agent'){
                    //direct agent
                    $silverAgentData = getUplineCommission(Auth::id());
                    $silverComm = $request['amount']*($silverAgentData['commission']/100);
                    $request['silver_agent_commission'] = $silverComm;
                    $request['silver_id'] = $silverAgentData['user_id'];

                    //upline agent - gold-agent
                    $goldAgentData = getUplineCommission($silverAgentData['user_id']);
                    $goldPercent = $goldAgentData['commission'] - $silverAgentData['commission'];
                    $goldComm = $request['amount']*($goldPercent/100);
                    $request['gold_agent_commission'] = $goldComm;
                    $request['agent_id'] = $goldAgentData['user_id'];

                    //upline agent - master-agent
                    $masterAgentData = getUplineCommission($goldAgentData['user_id']);
                    $MAPercent = $masterAgentData['commission'] - $goldAgentData['commission'];
                    $MAComm = $request['amount']*($MAPercent/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                $request['plasada'] = ($request['amount']*($game->plasada/100));
            }

            $bet = Bet::create($request->all());
            $tx = new Transaction();
            $tx->user_id = Auth::id();
            $tx->bet_id = $bet->id;
            $tx->amount = $bet->amount;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'bet-'.$request['bet'];
            $tx->direction = 'out';
            $tx->remarks = 'place bet to '.$request['bet'];
            $tx->save();

            $betOne = Bet::where('fight_id',$fight->id)->where('bet','x1')->sum('amount')+$fight->ghost_bet_blue;
            $betTwo = Bet::where('fight_id',$fight->id)->where('bet','x2')->sum('amount')+$fight->ghost_bet_grey;
            $betFive = Bet::where('fight_id',$fight->id)->where('bet','x5')->sum('amount')+$fight->ghost_bet_red;
            $betTen = Bet::where('fight_id',$fight->id)->where('bet','x10')->sum('amount')+$fight->ghost_bet_yellow;
            $betTwenty = Bet::where('fight_id',$fight->id)->where('bet','x20')->sum('amount')+$fight->ghost_bet_white;
            $betForty = Bet::where('fight_id',$fight->id)->where('bet','x40')->sum('amount')+$fight->ghost_bet_pink;

            $myBetOne = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x1')->sum('amount');
            $myBetTwo = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x2')->sum('amount');
            $myBetFive = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x5')->sum('amount');
            $myBetTen = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x10')->sum('amount');
            $myBetTwenty = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x20')->sum('amount');
            $myBetForty = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','x40')->sum('amount');


            $betData = [
                'msg' => 'Bet has been placed!',
                'betOne' => $betOne,
                'betTwo'=> $betTwo,
                'betFive'=> $betFive,
                'betTen'=> $betTen,
                'betTwenty'=> $betTwenty,
                'betForty'=> $betForty,
                'myBetOne' => $myBetOne,
                'myBetTwo' => $myBetTwo,
                'myBetFive' => $myBetFive,
                'myBetTen' => $myBetTen,
                'myBetTwenty' => $myBetTwenty,
                'myBetForty' => $myBetForty,
                'balance' => $wallet->balance
            ];

            $allBets = DB::table('bets as b')
                        ->join('users as u','b.user_id','=','u.id')
                        ->where('b.fight_id',$request['fight_id'])
                        ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                        ->orderBy('id','DESC')->get();

            event(new PlaceBet($betData,$event->event_id));
            event(new AllBets($allBets,$event->event_id));
            return response()->json($betData);
        }
    }

    public function placeRGGhostBet(Request $request){
        $fight = Fight::find($request['fight_id']);

        if($request['bet'] == 'x1'){
            $fight->ghost_bet_blue = $fight->ghost_bet_blue+$request['amount'];
        }

        if($request['bet'] == 'x2'){
            $fight->ghost_bet_grey = $fight->ghost_bet_grey+$request['amount'];
        }

        if($request['bet'] == 'x5'){
            $fight->ghost_bet_red = $fight->ghost_bet_red+$request['amount'];
        }

        if($request['bet'] == 'x10'){
            $fight->ghost_bet_yellow = $fight->ghost_bet_yellow+$request['amount'];
        }

        if($request['bet'] == 'x20'){
            $fight->ghost_bet_white = $fight->ghost_bet_white+$request['amount'];
        }

        if($request['bet'] == 'x40'){
            $fight->ghost_bet_pink = $fight->ghost_bet_pink+$request['amount'];
        }

        $fight->save();

        $betOne = Bet::where('fight_id',$fight->id)->where('bet','x1')->sum('amount')+$fight->ghost_bet_blue;
        $betTwo = Bet::where('fight_id',$fight->id)->where('bet','x2')->sum('amount')+$fight->ghost_bet_grey;
        $betFive = Bet::where('fight_id',$fight->id)->where('bet','x5')->sum('amount')+$fight->ghost_bet_red;
        $betTen = Bet::where('fight_id',$fight->id)->where('bet','x10')->sum('amount')+$fight->ghost_bet_yellow;
        $betTwenty = Bet::where('fight_id',$fight->id)->where('bet','x20')->sum('amount')+$fight->ghost_bet_white;
        $betForty = Bet::where('fight_id',$fight->id)->where('bet','x40')->sum('amount')+$fight->ghost_bet_pink;

        return response()->json([
            'betOne' => $betOne,
            'betTwo' => $betTwo,
            'betFive' => $betFive,
            'betTen' => $betTen,
            'betTwenty' => $betTwenty,
            'betForty' => $betForty
        ]);
    }

    public function declareWinnerRG(Request $request){
        $event = Event::find($request['event_id']);
        $game = Game::find($event->game_id);
        $deduction = Setting::where('setting_name','operator_commission')->first();
        $playerDeduction = $deduction->value+$game->plasada;

        $fight = Fight::find($request['id']);
        $fight->status = 'finished';
        $fight->result = $request['result'];
        $fight->save();
        //create new Fight
        $newFight = new Fight();
        $newFight->event_id = $request['event_id'];
        $newFight->fight_number = $fight->fight_number+1;
        $newFight->status = 'not_open';
        $newFight->save();

        //update current fight in event
        $event = Event::find($request['event_id']);
        $event->active_fight = $newFight->id;
        $event->save();

        $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();

        $declareData = [
            'result' => removeUnderscore($request['result']),
            'status' => 'not_open',
            'fight_id' => $newFight->id,
            'fight_number' => $newFight->fight_number,
            'wins' => $wins
        ];

        Bet::where('fight_id',$request['id'])->update(['result'=>$request['result']]);

        //insert code here to calculate total agent commission in this fight

        if($request['result'] != '7x' && $request['result'] != '2x'){

            $gameBets = Bet::where('fight_id',$request['id'])->where('bet',$request['result'])->get();
            $multiplier = 15;

            foreach($gameBets as $gb){
                // give winnings to player
                if($request['result'] == 'x1'){
                    $multiplier = 1;
                }elseif($request['result'] == 'x2'){
                    $multiplier = 2;
                }elseif($request['result'] == 'x5'){
                    $multiplier = 5;
                }elseif($request['result'] == 'x10'){
                    $multiplier = 10;
                }elseif($request['result'] == 'x20'){
                    $multiplier = 20;
                }elseif($request['result'] == 'x40'){
                    $multiplier = 40;
                }

                $amount_won = $gb->amount*$multiplier-(($gb->amount*$multiplier)*($playerDeduction/100))+$gb->amount;

                Bet::find($gb->id)->update(['amount_won'=>$amount_won]);
                $wallet = Wallet::where('user_id',$gb->user_id)->first();
                $wallet->balance = $wallet->balance+$amount_won;
                $wallet->save();

                $data =[
                    'balance' => $wallet->balance,
                    'user_id' => $wallet->user_id,
                    'percent' => '100',
                    'betAmount' => $gb->amount,
                    'result' => $request['result']
                ];

                $event = Event::find($gb->event_id);
                event(new SendNotification($data,$event->event_id));

                $tx = new Transaction();
                $tx->user_id = $gb->user_id;
                $tx->bet_id = $gb->id;
                $tx->amount = $amount_won;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'bet-'.$request['result'];
                $tx->direction = 'in';
                $tx->remarks = 'winnings from bet '.$request['result'];
                $tx->save();

                //add plasada to superadmin commission
                $wallet = Wallet::where('user_id',1)->first();
                $wallet->commission = $wallet->commission+$gb->plasada;
                $wallet->save();

                $tx = new Transaction();
                $tx->user_id = 1;
                $tx->bet_id = $gb->id;
                $tx->amount = $gb->plasada;
                $tx->ending_balance = $wallet->commission;
                $tx->type = 'plasada-in';
                $tx->direction = 'in';
                $tx->remarks = 'plasada from player bet';
                $tx->save();

                //credit commissions to operator
                if($gb->op_id != null){
                    $wallet = Wallet::where('user_id',$gb->op_id)->first();
                    $wallet->commission = $wallet->commission+$gb->operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $gb->op_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to sub operator
                if($gb->subop_id != null){
                    $wallet = Wallet::where('user_id',$gb->subop_id)->first();
                    $wallet->commission = $wallet->commission+$gb->sub_operator_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $gb->subop_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->sub_operator_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to master agent
                if($gb->ma_id != null){
                    $wallet = Wallet::where('user_id',$gb->ma_id)->first();
                    $wallet->commission = $wallet->commission+$gb->master_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $gb->ma_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->master_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                //credit commissions to fold agent
                if($gb->agent_id != null){
                    $wallet = Wallet::where('user_id',$gb->agent_id)->first();
                    $wallet->commission = $wallet->commission+$gb->gold_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $gb->agent_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->gold_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }

                if($gb->silver_id != null){
                    $wallet = Wallet::where('user_id',$gb->silver_id)->first();
                    $wallet->commission = $wallet->commission+$gb->silver_agent_commission;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = $gb->silver_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->silver_agent_commission;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'commission-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'commission from player bet';
                    $tx->save();
                }
            }

            event(new DeclareWinner($declareData,$event->event_id));
            event(new PlayerBet("NOT OPEN",$event->event_id));
            return response()->json($declareData);

        }else{

                $gameBets = Bet::where('fight_id',$request['id'])->get();

                foreach($gameBets as $gb){
                    // give winnings to player
                    if($request['result'] == '2x'){
                        $multiplier = 3;
                    }
                    if($request['result'] == '7x'){
                        $multiplier = 8;
                    }

                    $amount_won = $gb->amount*$multiplier-(($gb->amount*$multiplier)*(($playerDeduction)/100));

                    Bet::find($gb->id)->update(['amount_won'=>$amount_won]);
                    $wallet = Wallet::where('user_id',$gb->user_id)->first();
                    $wallet->balance = $wallet->balance+$amount_won;
                    $wallet->save();

                    $data =[
                        'balance' => $wallet->balance,
                        'user_id' => $wallet->user_id,
                        'percent' => '100',
                        'betAmount' => $gb->amount,
                        'result' => $request['result']
                    ];

                    $event = Event::find($gb->event_id);
                    event(new SendNotification($data,$event->event_id));

                    $tx = new Transaction();
                    $tx->user_id = 1;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $amount_won;
                    $tx->ending_balance = $wallet->balance;
                    $tx->type = 'bet-'.$request['result'];
                    $tx->direction = 'in';
                    $tx->remarks = 'winnings from bet '.$request['result'];
                    $tx->save();

                    //add plasada to superadmin commission
                    $wallet = Wallet::where('user_id',1)->first();
                    $wallet->commission = $wallet->commission+$gb->plasada;
                    $wallet->save();

                    $tx = new Transaction();
                    $tx->user_id = 1;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $gb->plasada;
                    $tx->ending_balance = $wallet->commission;
                    $tx->type = 'plasada-in';
                    $tx->direction = 'in';
                    $tx->remarks = 'plasada from player bet';
                    $tx->save();

                    //credit commissions to operator
                    if($gb->op_id != null){
                        $wallet = Wallet::where('user_id',$gb->op_id)->first();
                        $wallet->commission = $wallet->commission+$gb->operator_commission;
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $gb->op_id;
                        $tx->bet_id = $gb->id;
                        $tx->amount = $gb->operator_commission;
                        $tx->ending_balance = $wallet->commission;
                        $tx->type = 'commission-in';
                        $tx->direction = 'in';
                        $tx->remarks = 'commission from player bet';
                        $tx->save();
                    }

                    //credit commissions to sub operator
                    if($gb->subop_id != null){
                        $wallet = Wallet::where('user_id',$gb->subop_id)->first();
                        $wallet->commission = $wallet->commission+$gb->sub_operator_commission;
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $gb->subop_id;
                        $tx->bet_id = $gb->id;
                        $tx->amount = $gb->sub_operator_commission;
                        $tx->ending_balance = $wallet->commission;
                        $tx->type = 'commission-in';
                        $tx->direction = 'in';
                        $tx->remarks = 'commission from player bet';
                        $tx->save();
                    }

                    //credit commissions to master agent
                    if($gb->ma_id != null){
                        $wallet = Wallet::where('user_id',$gb->ma_id)->first();
                        $wallet->commission = $wallet->commission+$gb->master_agent_commission;
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $gb->ma_id;
                        $tx->bet_id = $gb->id;
                        $tx->amount = $gb->master_agent_commission;
                        $tx->ending_balance = $wallet->commission;
                        $tx->type = 'commission-in';
                        $tx->direction = 'in';
                        $tx->remarks = 'commission from player bet';
                        $tx->save();
                    }

                    //credit commissions to fold agent
                    if($gb->agent_id != null){
                        $wallet = Wallet::where('user_id',$gb->agent_id)->first();
                        $wallet->commission = $wallet->commission+$gb->gold_agent_commission;
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $gb->agent_id;
                        $tx->bet_id = $gb->id;
                        $tx->amount = $gb->gold_agent_commission;
                        $tx->ending_balance = $wallet->commission;
                        $tx->type = 'commission-in';
                        $tx->direction = 'in';
                        $tx->remarks = 'commission from player bet';
                        $tx->save();
                    }

                    if($gb->silver_id != null){
                        $wallet = Wallet::where('user_id',$gb->silver_id)->first();
                        $wallet->commission = $wallet->commission+$gb->silver_agent_commission;
                        $wallet->save();

                        $tx = new Transaction();
                        $tx->user_id = $gb->silver_id;
                        $tx->bet_id = $gb->id;
                        $tx->amount = $gb->silver_agent_commission;
                        $tx->ending_balance = $wallet->commission;
                        $tx->type = 'commission-in';
                        $tx->direction = 'in';
                        $tx->remarks = 'commission from player bet';
                        $tx->save();
                    }
                }

            event(new DeclareWinner($declareData,$event->event_id));
            event(new PlayerBet("NOT OPEN",$event->event_id));
            $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();
            return response()->json($declareData);

        }

    }
}
