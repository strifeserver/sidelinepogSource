<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Game;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Bet;
use App\Models\Fight;

use App\Http\Controllers\BetController;
use App\Http\Controllers\EventsController;


Route::get('/game-bets/{id?}', function ($id = null) {
    if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
        $fight = [];
        $event = \App\Models\Event::find($id);
        $betMeron = 0;
        $betWala = 0;
        $myBetMeron = 0;
        $myBetWala = 0;
        $myBetDraw = 0;
        $odds = 0;
        $wins = collect([]);
        if($event){
            $game = Game::find($event->game_id);
            $settings = \App\Models\Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = $settings->value+$game->plasada;
            $fight = \App\Models\Fight::find($event->active_fight);
            $betMeron = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','meron')->sum('amount') + $fight->ghost_bet_wala;
            $betWala = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','wala')->sum('amount') + $fight->ghost_bet_meron;
            $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
            $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();

            $myBetMeron = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','meron')->where('user_id',Auth::id())->sum('amount');
            $myBetWala = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','wala')->where('user_id',Auth::id())->sum('amount');
            $myBetDraw = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','draw')->where('user_id',Auth::id())->sum('amount');
            //return response($wins);
        }else{
            $fight = collect($fight);
        }
        return view('mpl.betting',compact('event','fight','betMeron','betWala','odds','wins','myBetMeron','myBetWala','myBetDraw'));
    }

    return redirect()->route('dashboard');

})->name('game.bets');


Route::post('/two-place-bet',[BetController::class,'placeBet'])->name('two.bet');
Route::post('/change-teams',[EventsController::class,'updateTeams'])->name('change.team');


Route::get('/game-declare/{id}', function ($id) {

    if(Auth::user()->type == 'player'){
        return redirect()->route('select.game');
    }

    $fight = [];
    $event = \App\Models\Event::find($id);
    $settings = \App\Models\Setting::find(1);
    $playerDeduction = $settings->player_deduction;
    if($event){
        $fight = \App\Models\Fight::find($event->active_fight);
        $fights = \App\Models\Fight::where('event_id',$id)->where('status','finished')->get();
        $betMeron = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','meron')->sum('amount');
        $betWala = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','wala')->sum('amount');
        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
        $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();
        $allBets = DB::table('bets as b')
                    ->join('users as u','b.user_id','=','u.id')
                    ->where('b.fight_id',$fight->id)
                    ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                    ->orderBy('id','DESC')->get();
    }else{
        $fight = collect($fight);
    }

    return view('mpl.show',compact('event','fight','betMeron','betWala','odds','wins','allBets','fights'));
})->name('game.declare');

?>
