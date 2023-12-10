<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Game;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Bet;
use App\Models\Fight;

use App\Http\Controllers\CGController;


Route::get('/dropball', function () {
    $game =  Game::where('name','dropball')->where('status','open')->first();
    if(Auth::check()){
        if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
            $fight = [];
            $event = Event::where('game_id',$game->id)->where('status','open')->latest('id')->first();
            $settings = Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = $settings->value+$game->plasada;
            $betJack = 0;
            $betQueen = 0;
            $betKing = 0;
            $betNine = 0;
            $betTen = 0;
            $betAce = 0;

            $myBetJack = 0;
            $myBetQueen = 0;
            $myBetKing = 0;
            $myBetNine = 0;
            $myBetTen = 0;
            $myBetAce = 0;
            $wins = collect([]);
            if($event){
                $fight = Fight::find($event->active_fight);
                $betJack = Bet::where('fight_id',$fight->id)->where('bet','jack')->sum('amount')+$fight->ghost_bet_blue;
                $betQueen = Bet::where('fight_id',$fight->id)->where('bet','queen')->sum('amount')+$fight->ghost_bet_grey;
                $betKing = Bet::where('fight_id',$fight->id)->where('bet','king')->sum('amount')+$fight->ghost_bet_red;
                $betNine = Bet::where('fight_id',$fight->id)->where('bet','nine')->sum('amount')+$fight->ghost_bet_yellow;
                $betTen = Bet::where('fight_id',$fight->id)->where('bet','ten')->sum('amount')+$fight->ghost_bet_white;
                $betAce = Bet::where('fight_id',$fight->id)->where('bet','ace')->sum('amount')+$fight->ghost_bet_pink;

                $myBetJack = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','jack')->sum('amount');
                $myBetQueen = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','queen')->sum('amount');
                $myBetKing = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','king')->sum('amount');
                $myBetNine = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','nine')->sum('amount');
                $myBetTen = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','ten')->sum('amount');
                $myBetAce = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','ace')->sum('amount');

                $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number','id')->orderBy('id','DESC')->get();
                //return response($wins);
            }else{
                $fight = collect($fight);
            }
            return view('dropball.betting',compact('event','fight','betJack','betQueen','betKing','betNine','betTen','betAce','wins','myBetJack','myBetQueen','myBetKing','myBetNine','myBetTen','myBetAce'));
        }
    }

    return redirect()->route('dashboard');
})->name('dropball');


Route::post('dp-place-bet',[CGController::class,'placeCGBet'])->name('dp.placeBet');


Route::get('/show-event-dp/{id}', function ($id) {

    if(Auth::user()->type == 'player'){
        return redirect()->route('select.game');
    }

    $fight = [];
    $event = \App\Models\Event::find($id);
    $settings = \App\Models\Setting::find(1);
    $playerDeduction = $settings->player_deduction;
    $betJack = 0;
    $betQueen = 0;
    $betKing = 0;
    $betNine = 0;
    $betTen = 0;
    $betAce = 0;

    $myBetJack = 0;
    $myBetQueen = 0;
    $myBetKing = 0;
    $myBetNine = 0;
    $myBetTen = 0;
    $myBetAce = 0;
    if($event){
        $fight = \App\Models\Fight::find($event->active_fight);
        $betJack = Bet::where('fight_id',$fight->id)->where('bet','jack')->sum('amount');
        $betQueen = Bet::where('fight_id',$fight->id)->where('bet','queen')->sum('amount');
        $betKing = Bet::where('fight_id',$fight->id)->where('bet','king')->sum('amount');
        $betNine = Bet::where('fight_id',$fight->id)->where('bet','nine')->sum('amount');
        $betTen = Bet::where('fight_id',$fight->id)->where('bet','ten')->sum('amount');
        $betAce = Bet::where('fight_id',$fight->id)->where('bet','ace')->sum('amount');

        $ghostJack = $fight->ghost_bet_blue;
        $ghostQueen = $fight->ghost_bet_grey;
        $ghostKing = $fight->ghost_bet_red;
        $ghostNine = $fight->ghost_bet_yellow;
        $ghostTen = $fight->ghost_bet_white;
        $ghostAce = $fight->ghost_bet_pink;

        $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number','id')->orderBy('id','DESC')->get();

        $allBets = DB::table('bets as b')
                    ->join('users as u','b.user_id','=','u.id')
                    ->where('b.fight_id',$fight->id)
                    ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                    ->orderBy('id','DESC')->get();
    }else{
        $fight = collect($fight);
    }

    return view('dropball.declare',compact('event','fight','betJack','betQueen','betKing','betNine','betTen','betAce','wins','allBets'));

})->name('show.event.dp');


Route::post('/declare-winner-cg',[CGController::class,'declareWinnerCG'])->name('declare.cgwinner');
Route::post('/place-cg-ghost-bet',[CGController::class,'placeCGGhostBet'])->name('cgplace.ghost');
