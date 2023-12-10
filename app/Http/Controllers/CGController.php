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



class CGController extends Controller
{
    public function placeCGBet(Request $request){
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
                    $operatorData['commission'] = 4;
                    $operatorComm = $request['amount']*($operatorData['commission']/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }


                if(Auth::user()->referrer_type == 'sub-operator'){
                    //direct agent

                    $subOperatorData = getUplineCommission(Auth::id());
                    $subOperatorData['commission'] = 3;
                    $subOperatorComm = $request['amount']*($subOperatorData['commission']/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOperatorData['user_id'];

                    //upline agent
                    $operatorData =  getUplineCommission(Auth::user()->referred_by);
                    $opPercent = $operatorData['commission']-$subOperatorData['commission'];
                    $opPercent = 1;
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                if(Auth::user()->referrer_type == 'master-agent'){
                    //direct agent
                    $masterAgentData = getUplineCommission(Auth::id());
                    $masterAgentData['commission'] = 2;
                    $MAComm = $request['amount']*($masterAgentData['commission']/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOpPercent = 1;
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $opPercent = 1;
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                if(Auth::user()->referrer_type == 'gold-agent'){
                    //direct agent
                    $goldAgentData = getUplineCommission(Auth::id());
                    $goldAgentData['commission'] = 1;
                    $goldComm = $request['amount']*($goldAgentData['commission']/100);
                    $request['gold_agent_commission'] = $goldComm;
                    $request['agent_id'] = $goldAgentData['user_id'];

                    //upline agent - master-agent
                    $masterAgentData = getUplineCommission($goldAgentData['user_id']);
                    $MAPercent = $masterAgentData['commission'] - $goldAgentData['commission'];
                    $MAPercent = 1;
                    $MAComm = $request['amount']*($MAPercent/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOpPercent = 1;
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $opPercent = 1;
                    $operatorComm = $request['amount']*($opPercent/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }

                if(Auth::user()->referrer_type == 'silver-agent'){
                    //direct agent
                    $silverAgentData = getUplineCommission(Auth::id());
                    $silverAgentData['commission'] = 0.5;
                    $silverComm = $request['amount']*($silverAgentData['commission']/100);
                    $request['silver_agent_commission'] = $silverComm;
                    $request['silver_id'] = $silverAgentData['user_id'];

                    //upline agent - gold-agent
                    $goldAgentData = getUplineCommission($silverAgentData['user_id']);
                    $goldPercent = $goldAgentData['commission'] - $silverAgentData['commission'];
                    $goldPercent = 0.5;
                    $goldComm = $request['amount']*($goldPercent/100);
                    $request['gold_agent_commission'] = $goldComm;
                    $request['agent_id'] = $goldAgentData['user_id'];

                    //upline agent - master-agent
                    $masterAgentData = getUplineCommission($goldAgentData['user_id']);
                    $MAPercent = $masterAgentData['commission'] - $goldAgentData['commission'];
                    $MAPercent = 1;
                    $MAComm = $request['amount']*($MAPercent/100);
                    $request['master_agent_commission'] = $MAComm;
                    $request['ma_id'] = $masterAgentData['user_id'];

                    //upline agent - sub-operator
                    $subOpData = getUplineCommission($masterAgentData['user_id']);
                    $subOpPercent = $subOpData['commission'] - $masterAgentData['commission'];
                    $subOpPercent = 1;
                    $subOperatorComm = $request['amount']*($subOpPercent/100);
                    $request['sub_operator_commission'] = $subOperatorComm;
                    $request['subop_id'] = $subOpData['user_id'];

                    //upline agent - operator

                    $operatorData =  getUplineCommission($subOpData['user_id']);
                    $opPercent = $operatorData['commission']-$subOpData['commission'];
                    $opPercent = 1;
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

            $betBlue = Bet::where('fight_id',$request['fight_id'])->where('bet','blue')->sum('amount')+$fight->ghost_bet_blue;
            $betGrey = Bet::where('fight_id',$request['fight_id'])->where('bet','grey')->sum('amount')+$fight->ghost_bet_grey;
            $betRed = Bet::where('fight_id',$request['fight_id'])->where('bet','red')->sum('amount')+$fight->ghost_bet_red;
            $betYellow = Bet::where('fight_id',$request['fight_id'])->where('bet','yellow')->sum('amount')+$fight->ghost_bet_yellow;
            $betWhite = Bet::where('fight_id',$request['fight_id'])->where('bet','white')->sum('amount')+$fight->ghost_bet_white;
            $betPink = Bet::where('fight_id',$request['fight_id'])->where('bet','pink')->sum('amount')+$fight->ghost_bet_pink;

            $myBetBlue = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','blue')->sum('amount');
            $myBetGrey = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','grey')->sum('amount');
            $myBetRed = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','red')->sum('amount');
            $myBetYellow = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','yellow')->sum('amount');
            $myBetWhite = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','white')->sum('amount');
            $myBetPink = Bet::where('fight_id',$request['fight_id'])->where('user_id',Auth::id())->where('bet','pink')->sum('amount');


            $betData = [
                'msg' => 'Bet has been placed!',
                'betBlue' => $betBlue,
                'betGrey'=> $betGrey,
                'betRed'=> $betRed,
                'betYellow'=> $betYellow,
                'betWhite'=> $betWhite,
                'betPink'=> $betPink,
                'myBetBlue' => $myBetBlue,
                'myBetGrey' => $myBetGrey,
                'myBetRed' => $myBetRed,
                'myBetYellow' => $myBetYellow,
                'myBetWhite' => $myBetWhite,
                'myBetPink' => $myBetPink,
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

    public function placeCGGhostBet(Request $request){
        $fight = Fight::find($request['fight_id']);

        if($request['bet'] == 'blue'){
            $fight->ghost_bet_blue = $fight->ghost_bet_blue+$request['amount'];
        }

        if($request['bet'] == 'grey'){
            $fight->ghost_bet_grey = $fight->ghost_bet_grey+$request['amount'];
        }

        if($request['bet'] == 'red'){
            $fight->ghost_bet_red = $fight->ghost_bet_red+$request['amount'];
        }

        if($request['bet'] == 'yellow'){
            $fight->ghost_bet_yellow = $fight->ghost_bet_yellow+$request['amount'];
        }

        if($request['bet'] == 'white'){
            $fight->ghost_bet_white = $fight->ghost_bet_white+$request['amount'];
        }

        if($request['bet'] == 'pink'){
            $fight->ghost_bet_pink = $fight->ghost_bet_pink+$request['amount'];
        }

        $fight->save();
    }


    public function declareWinnerCG(Request $request){
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

        $res = explode(',',$request['result'],3);

        if(count(array_unique($res)) === 1 && $res[0] == 'red' && $res[1] == 'red' && $res[2] == 'red'){

            $gameBets = Bet::where('fight_id',$request['id'])->where('bet',$res[0])->get();
            $multiplier = 15;

            foreach($gameBets as $gb){
                // give winnings to player
                $amount_won = ($gb->amount*$multiplier) - ($gb->amount*(($playerDeduction/2)/100));
                Bet::find($gb->id)->update(['amount_won'=>$amount_won]);
                $wallet = Wallet::where('user_id',$gb->user_id)->first();
                $wallet->balance = $wallet->balance+$amount_won;
                $wallet->save();

                $data =[
                    'balance' => $wallet->balance,
                    'user_id' => $wallet->user_id,
                    'percent' => '100',
                    'betAmount' => $gb->amount,
                    'result' => $res[0]
                ];

                $event = Event::find($gb->event_id);
                event(new SendNotification($data,$event->event_id));

                $tx = new Transaction();
                $tx->user_id = $gb->user_id;
                $tx->bet_id = $gb->id;
                $tx->amount = $amount_won;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'bet-'.$res[0];
                $tx->direction = 'in';
                $tx->remarks = 'winnings from bet '.$res[0];
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
                if(in_array($gb->bet,$res)){
                    // give winnings to player
                    $amount_won = ($gb->amount*2) - (($gb->amount*2)*(($playerDeduction/2)/100));
                    Bet::find($gb->id)->update(['amount_won'=>$amount_won]);
                    $wallet = Wallet::where('user_id',$gb->user_id)->first();
                    $wallet->balance = $wallet->balance+$amount_won;
                    $wallet->save();

                    $data =[
                        'balance' => $wallet->balance,
                        'user_id' => $wallet->user_id,
                        'percent' => '100',
                        'betAmount' => $gb->amount,
                        'result' => $res[0]
                    ];

                    $event = Event::find($gb->event_id);
                    event(new SendNotification($data,$event->event_id));

                    $tx = new Transaction();
                    $tx->user_id = $gb->user_id;
                    $tx->bet_id = $gb->id;
                    $tx->amount = $amount_won;
                    $tx->ending_balance = $wallet->balance;
                    $tx->type = 'bet-'.$gb->bet;
                    $tx->direction = 'in';
                    $tx->remarks = 'winnings from bet '.$gb->bet;
                    $tx->save();
                }

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
