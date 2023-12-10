<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Game;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Bet;
use App\Models\Fight;

use App\Http\Controllers\PlinkoController;


Route::get('/plinko', function () {
    $game =  Game::where('name','PLINKO')->first();
    if(Auth::check()){
        if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
            $fight = [];
            $event = Event::where('game_id',$game->id)->latest('id')->first();
            $settings = Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = $settings->value+$game->plasada;
            $betRed = 0;
            $betBlue = 0;
            $betYellow = 0;
            $betWhite = 0;

            $myBetRed = 0;
            $myBetBlue = 0;
            $myBetYellow = 0;
            $myBetWhite = 0;

            $wins = collect([]);
            if($event){
                $fight = Fight::find($event->active_fight);
                $betRed = Bet::where('fight_id',$fight->id)->where('bet','red')->sum('amount')+$fight->ghost_bet_red;
                $betBlue = Bet::where('fight_id',$fight->id)->where('bet','blue')->sum('amount')+$fight->ghost_bet_blue;
                $betYellow = Bet::where('fight_id',$fight->id)->where('bet','yellow')->sum('amount')+$fight->ghost_bet_yellow;
                $betWhite= Bet::where('fight_id',$fight->id)->where('bet','white')->sum('amount')+$fight->ghost_bet_white;

                $myBetRed = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','red')->sum('amount');
                $myBetBlue = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','blue')->sum('amount');
                $myBetYellow = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','yellow')->sum('amount');
                $myBetWhite = Bet::where('fight_id',$fight->id)->where('user_id',Auth::id())->where('bet','white')->sum('amount');

                $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number','id')->orderBy('id','DESC')->get();
                //return response($wins);
            }else{
                $fight = collect($fight);
            }
            return view('plinko.betting',compact('event','fight','betRed','betBlue','betYellow','betWhite','wins','myBetRed','myBetBlue','myBetYellow','myBetWhite'));
        }
    }

    return redirect()->route('dashboard');
})->name('plinko');

Route::post('pl-place-bet',[PlinkoController::class,'placePlinkoBet'])->name('pl.placeBet');

Route::get('/show-event-pl/{id}', function ($id) {

    if(Auth::user()->type == 'player'){
        return redirect()->route('select.game');
    }

    $fight = [];
    $event = \App\Models\Event::find($id);
    $settings = \App\Models\Setting::find(1);
    $playerDeduction = $settings->player_deduction;
    $betRed = 0;
    $betBlue = 0;
    $betYellow = 0;
    $betWhite = 0;

    $myBetRed = 0;
    $myBetBlue = 0;
    $myBetYellow = 0;
    $myBetWhite = 0;

    $ghostRed = 0;
    $ghostBlue = 0;
    $ghostYellow = 0;
    $ghostWhite = 0;

    if($event){
        $fight = \App\Models\Fight::find($event->active_fight);

        $betRed = Bet::where('fight_id',$fight->id)->where('bet','red')->sum('amount');
        $betBlue = Bet::where('fight_id',$fight->id)->where('bet','blue')->sum('amount');
        $betYellow = Bet::where('fight_id',$fight->id)->where('bet','yellow')->sum('amount');
        $betWhite= Bet::where('fight_id',$fight->id)->where('bet','white')->sum('amount');

        $ghostRed = $fight->ghost_bet_red;
        $ghostBlue = $fight->ghost_bet_blue;
        $ghostYellow = $fight->ghost_bet_yellow;
        $ghostWhite = $fight->ghost_bet_white;

        $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number','id')->orderBy('id','DESC')->get();

        $allBets = DB::table('bets as b')
                    ->join('users as u','b.user_id','=','u.id')
                    ->where('b.fight_id',$fight->id)
                    ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                    ->orderBy('id','DESC')->get();
    }else{
        $fight = collect($fight);
    }

    return view('plinko.declare',compact('event','fight','betRed','betBlue','betYellow','betWhite','wins','allBets','ghostRed','ghostBlue','ghostYellow','ghostWhite'));

})->name('show.event.pl');

Route::post('/declare-winner-pl',[PlinkoController::class,'declareWinnerPlinko'])->name('declare.plwinner');
Route::post('/place-rg-ghost-bet',[PlinkoController::class,'placePLGhostBet'])->name('plplace.ghost');

?>
