<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Game;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Bet;
use App\Models\Fight;

use App\Http\Controllers\CGController;


Route::get('/color-game', function () {
    $game =  Game::where('name','COLORGAME')->first();
    if(Auth::check()){
        if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
            $fight = [];
            $event = Event::where('game_id',$game->id)->where('status','open')->latest('id')->first();
            $settings = Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = $settings->value+$game->plasada;
            $betBlue = 0;
            $betGrey = 0;
            $betRed = 0;
            $betYellow = 0;
            $betWhite = 0;
            $betPink = 0;
            $myBetBlue = 0;
            $myBetGrey = 0;
            $myBetRed = 0;
            $myBetYellow = 0;
            $myBetWhite = 0;
            $myBetPink = 0;
            $wins = collect([]);
            if($event){
                $fight = Fight::find($event->active_fight);
                $betBlue = Bet::where('fight_id',$fight->id)->where('bet','blue')->sum('amount')+$fight->ghost_bet_blue;
                $betGrey = Bet::where('fight_id',$fight->id)->where('bet','grey')->sum('amount')+$fight->ghost_bet_grey;
                $betRed = Bet::where('fight_id',$fight->id)->where('bet','red')->sum('amount')+$fight->ghost_bet_red;
                $betYellow = Bet::where('fight_id',$fight->id)->where('bet','yellow')->sum('amount')+$fight->ghost_bet_yellow;
                $betWhite = Bet::where('fight_id',$fight->id)->where('bet','white')->sum('amount')+$fight->ghost_bet_white;
                $betPink = Bet::where('fight_id',$fight->id)->where('bet','pink')->sum('amount')+$fight->ghost_bet_pink;

                $myBetBlue = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','blue')->sum('amount');
                $myBetGrey = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','grey')->sum('amount');
                $myBetRed = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','red')->sum('amount');
                $myBetYellow = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','yellow')->sum('amount');
                $myBetWhite = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','white')->sum('amount');
                $myBetPink = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','pink')->sum('amount');

                $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();
                //return response($wins);
            }else{
                $fight = collect($fight);
            }
            return view('color.betting',compact('event','fight','betBlue','betGrey','betRed','betYellow','betWhite','betPink','wins','myBetBlue','myBetGrey','myBetRed','myBetYellow','myBetWhite','myBetPink'));
        }
    }

    return redirect()->route('dashboard');
})->name('color.game');


Route::post('cg-place-bet',[CGController::class,'placeCGBet'])->name('cg.placeBet');


Route::get('/show-event-cg/{id}', function ($id) {

    if(Auth::user()->type == 'player'){
        return redirect()->route('select.game');
    }

    $fight = [];
    $event = \App\Models\Event::find($id);
    $settings = \App\Models\Setting::find(1);
    $playerDeduction = $settings->player_deduction;
    $betBlue = 0;
    $betGrey = 0;
    $betRed = 0;
    $betYellow = 0;
    $betWhite = 0;
    $betPink = 0;
    $myBetBlue = 0;
    $myBetGrey = 0;
    $myBetRed = 0;
    $myBetYellow = 0;
    $myBetWhite = 0;
    $myBetPink = 0;
    if($event){
        $fight = \App\Models\Fight::find($event->active_fight);
        $betBlue = Bet::where('fight_id',$fight->id)->where('bet','blue')->sum('amount')+$fight->ghost_bet_blue;
        $betGrey = Bet::where('fight_id',$fight->id)->where('bet','grey')->sum('amount')+$fight->ghost_bet_grey;
        $betRed = Bet::where('fight_id',$fight->id)->where('bet','red')->sum('amount')+$fight->ghost_bet_red;
        $betYellow = Bet::where('fight_id',$fight->id)->where('bet','yellow')->sum('amount')+$fight->ghost_bet_yellow;
        $betWhite = Bet::where('fight_id',$fight->id)->where('bet','white')->sum('amount')+$fight->ghost_bet_white;
        $betPink = Bet::where('fight_id',$fight->id)->where('bet','pink')->sum('amount')+$fight->ghost_bet_pink;

        $myBetBlue = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','blue')->sum('amount');
        $myBetGrey = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','grey')->sum('amount');
        $myBetRed = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','red')->sum('amount');
        $myBetYellow = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','yellow')->sum('amount');
        $myBetWhite = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','white')->sum('amount');
        $myBetPink = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','pink')->sum('amount');
        $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();

        $allBets = DB::table('bets as b')
                    ->join('users as u','b.user_id','=','u.id')
                    ->where('b.fight_id',$fight->id)
                    ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                    ->orderBy('id','DESC')->get();
    }else{
        $fight = collect($fight);
    }

    return view('color.declare',compact('event','fight','betBlue','betGrey','betRed','betYellow','betWhite','betPink','wins','myBetBlue','myBetGrey','myBetRed','myBetYellow','myBetWhite','myBetPink','allBets'));

})->name('show.event.cg');


Route::post('/declare-winner-cg',[CGController::class,'declareWinnerCG'])->name('declare.cgwinner');
Route::post('/place-cg-ghost-bet',[CGController::class,'placeCGGhostBet'])->name('cgplace.ghost');
