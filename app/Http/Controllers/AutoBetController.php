<?php

namespace App\Http\Controllers;

use App\Events\AllBets;
use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Fight;
use Illuminate\Support\Facades\Auth;

use App\Events\PlaceBet;
use App\Models\Event;
use App\Models\Game;
use DB;

class AutoBetController extends Controller
{
    public function placeBet(Request $request){
        return response('success');
        
        $fight = Fight::find($request['fight_id']);
        $event = Event::find($fight->event_id);
        $ded = Setting::where('setting_name','player_deduction')->first();
        $betDraw = Bet::where('fight_id',$request['fight_id'])->where('bet','draw')->sum('amount');

        $up = getUplineCommission($request['user_id']);

        if($up['type'] != 'operator'){
            while ($up['type'] != 'operator'){
                $up = getUplineCommission($up['user_id']);
            }
        }

        if($request['bet'] == 'draw'){
            if($betDraw+$request['amount'] > 1000){
                return response()->json(['msg'=>'Unable to place bet. Only Max of PHP1000 can be placed on draw!'],409);
            }
        }


        $request['amount'] = bcdiv($request['amount'],1,2);

        $plasada = $ded ->value - $up['commission'];

        //return response($plasada);
        $playerDeduction = $ded->value;

        if($fight->status == 'closed' || $fight->status == 'finished' || $fight->status == 'not_open'){
            return response()->json(['msg'=>'Unable to place bet. Betting not open!'],409);
        }

        $wallet = Wallet::where('user_id',$request['user_id'])->first();

        if($wallet->balance < $request['amount']){
            return response()->json([
                'msg' => "Insufficient wallet balance to place bet!"
            ],403);
        }else{
            $wallet->balance = $wallet->balance-$request['amount'];
            $wallet->save();

            if(Auth::user()->type == 'player'){

                $request['plasada'] = ($request['amount']*($plasada/100));

                if(Auth::user()->referrer_type == 'operator'){
                    $operatorData = getUplineCommission($request['user_id']);
                    $operatorComm = $request['amount']*($operatorData['commission']/100);
                    $request['operator_commission'] = $operatorComm;
                    $request['op_id'] = $operatorData['user_id'];
                }


                if(Auth::user()->referrer_type == 'sub-operator'){

                    //direct agent
                    $subOperatorData = getUplineCommission($request['user_id']);
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
                    $masterAgentData = getUplineCommission($request['user_id']);
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
                    $goldAgentData = getUplineCommission($request['user_id']);
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
                    $silverAgentData = getUplineCommission($request['user_id']);
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

            }


            if($request['bet'] == 'draw'){
                $request['plasada'] = 0;
                $request['agent_commission'] = 0;
                $request['master_agent_commission'] = 0;
            }

            $bet = Bet::create($request->all());
            $tx = new Transaction();
            $tx->user_id = $request['user_id'];
            $tx->bet_id = $bet->id;
            $tx->amount = $bet->amount;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'bet-'.$request['bet'];
            $tx->direction = 'out';
            $tx->remarks = 'place bet to '.$request['bet'];
            $tx->save();

            $betMeron = Bet::where('fight_id',$request['fight_id'])->where('bet','meron')->sum('amount');
            $betWala = Bet::where('fight_id',$request['fight_id'])->where('bet','wala')->sum('amount');

            $myBetMeron = Bet::where('fight_id',$request['fight_id'])->where('user_id',$request['user_id'])->where('bet','meron')->sum('amount');
            $myBetWala = Bet::where('fight_id',$request['fight_id'])->where('user_id',$request['user_id'])->where('bet','wala')->sum('amount');
            $myBetDraw = Bet::where('fight_id',$request['fight_id'])->where('user_id',$request['user_id'])->where('bet','draw')->sum('amount');

            $odds = calculateOdds($betMeron,$betWala,$playerDeduction);

            $allBets = DB::table('bets as b')
                        ->join('users as u','b.user_id','=','u.id')
                        ->where('b.fight_id',$request['fight_id'])
                        ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                        ->orderBy('id','DESC')->get();

            $betData = [
                'betMeron' => (bcdiv($betMeron,1,2)),
                'betWala'=> (bcdiv($betWala,1,2)),
                'betDraw'=> (bcdiv($betDraw,1,2)),
                'percentMeron' => bcdiv($odds['meron'],1,2),
                'percentWala' => bcdiv($odds['wala'],1,2),
                'myBetMeron' => bcdiv($myBetMeron,1,2),
                'myBetWala' => bcdiv($myBetWala,1,2),
                'myBetDraw'=>bcdiv($myBetDraw,1,2)
            ];

            event(new PlaceBet($betData,$event->event_id));
            event(new AllBets($allBets,$event->event_id));

            return response()->json([
                'bet' => $bet,
                'balance' => bcdiv($wallet->balance,1,2),
                'betMeron' => bcdiv($betMeron,1,2),
                'betWala' => bcdiv($betWala,1,2),
                'betDraw' => bcdiv($betDraw,1,2),
                'odds' => $odds,
                'myBetMeron' => bcdiv($myBetMeron,1,2),
                'myBetWala' => bcdiv($myBetWala,1,2),
                'myBetDraw'=>bcdiv($myBetDraw,1,2)
            ],200);
        }
    }
}
