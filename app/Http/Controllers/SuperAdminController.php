<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function redeclareWinner(Request $request){
        $fight = Fight::find($request['fight_id']);
        $bets = PlayerBet::where('fight_id',$fight->id)->where('result',$fight->result)->get();
        $deduction = Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $deduction->value;
        $betMeron = PlayerBet::where('fight_id',$request['fight_id'])->where('user_id','!=',65)->where('bet','meron')->sum('amount');
        $betWala = PlayerBet::where('fight_id',$request['fight_id'])->where('user_id','!=',65)->where('bet','wala')->sum('amount');
        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
        $percentMeron = number_format($odds['meron'],2,'.','');
        $percentWala = number_format($odds['wala'],2,'.','');

        // deduct winnings from previous fight result;
        //return response($bets);
        foreach($bets as $b){
            $wallet = Wallet::where('user_id', $b->user_id)->first();
            $wallet->balance = $wallet->balance - $b->amount_won;
            $wallet->save();
        }

        //update fight new data
        $fight->result = $request['result'];
        $fight->save();

        //add credits to new winners
        $bets = PlayerBet::where('fight_id',$fight->id)->where('result',$request['result'])->get();

        if($request['result'] == 'meron'){
            $this->meronWalaWinnings($request['result'],$request['fight_id'],$percentMeron);
        }

        if($request['result'] == 'wala'){
            $this->meronWalaWinnings($request['result'],$request['fight_id'],$percentWala);
        }

        if($request['result'] == 'draw'){
            $this->drawWinnings($request['fight_id'],$playerDeduction);
        }

        if($request['result'] == 'cancelled'){
            $this->cancelFight($request['fight_id']);
        }

        return response()->json([
            'result' => removeUnderscore($request['result']),
            'status' => 'not_open',
            'fight_id' => $fight->id,
            'fight_number' => $fight->fight_number,
        ]);
    }

    public function drawWinnings($fightId,$playerDeduction){
        $winningBets = PlayerBet::where('fight_id',$fightId)->get();
        foreach($winningBets as $bet){
            // $totalWinnings = ($bet->amount * ($playerDeduction/100))*8;
            $totalWinnings = $bet->amount*8;
            if($bet->bet == 'draw'){
                PlayerBet::find($bet->id)->update(['amount_won'=>$totalWinnings]);
                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$totalWinnings;
                $wallet->save();
                $data =[
                    'balance' => $wallet->balance,
                    'user_id' => $wallet->user_id,
                    'percent' => 800,
                    'betAmount' => $bet->amount,
                    'result' => 'draw'
                ];

            }else{
                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$bet->amount;
                $wallet->save();
                $data =[
                    'balance' => $wallet->balance,
                    'user_id' => $wallet->user_id,
                    'percent' => 0,
                    'betAmount' => 0,
                    'result' => null
                ];
            }

            $playerBet = PlayerBet::find($bet->id);
            $playerBet->amount_won = 0;
            $playerBet->plasada = 0;
            $playerBet->agent_commission = 0;
            $playerBet->master_agent_commission = 0;
            $playerBet->save();

        }
    }

    public function cancelFight($fightId){

        $winningBets = PlayerBet::where('fight_id',$fightId)->get();

            foreach($winningBets as $bet){
                $wallet = Wallet::where('user_id',$bet->user_id)->first();
                $wallet->balance = $wallet->balance+$bet->amount;
                $wallet->save();

                $playerBet = PlayerBet::find($bet->id);
                $playerBet->amount_won = 0;
                $playerBet->plasada = 0;
                $playerBet->agent_commission = 0;
                $playerBet->master_agent_commission = 0;
                $playerBet->save();
                $data =[
                    'balance' => $wallet->balance,
                    'user_id' => $wallet->user_id,
                    'percent' => 0,
                    'betAmount' => 0,
                    'result' => 'cancelled'
                ];
            }
    }

    public function meronWalaWinnings($result,$fightId,$percentage){
        $winningBets = PlayerBet::where('fight_id',$fightId)->where('bet',$result)->get();
        foreach($winningBets as $bet){
            $totalWinnings = calculateAmountPercent($percentage,$bet->amount);
            PlayerBet::find($bet->id)->update(['amount_won'=>$totalWinnings]);
            $wallet = Wallet::where('user_id',$bet->user_id)->first();
            $wallet->balance = $wallet->balance+$totalWinnings;
            $wallet->save();
            $data =[
                'balance' => $wallet->balance,
                'user_id' => $wallet->user_id,
                'percent' => $percentage,
                'betAmount' => $bet->amount,
                'result' => $result
            ];
        }

    }
}
