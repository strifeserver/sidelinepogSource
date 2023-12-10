<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Game;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Bet;
use App\Models\Fight;

use App\Http\Controllers\RGController;


Route::get('/roulette', function () {
    $game =  Game::where('name','CW')->first();
    if(Auth::check()){
        if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
            $fight = [];
            $event = Event::where('game_id',$game->id)->latest('id')->first();
            $settings = Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = $settings->value+$game->plasada;
            $betOne = 0;
            $betTwo = 0;
            $betFive = 0;
            $betTen = 0;
            $betTwenty = 0;
            $betForty = 0;
            $myBetOne = 0;
            $myBetTwo = 0;
            $myBetFive = 0;
            $myBetTen = 0;
            $myBetTwenty = 0;
            $myBetForty = 0;
            $wins = collect([]);
            if($event){
                $fight = Fight::find($event->active_fight);
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

                $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();
                //return response($wins);
            }else{
                $fight = collect($fight);
            }
            return view('roulette.betting',compact('event','fight','betOne','betTwo','betFive','betTen','betTwenty','betForty','wins','myBetOne','myBetTwo','myBetFive','myBetTen','myBetTwenty','myBetForty'));
        }
    }

    return redirect()->route('dashboard');
})->name('roulette');

Route::post('rg-place-bet',[RGController::class,'placeRGBet'])->name('rg.placeBet');

Route::get('/show-event-rg/{id}', function ($id) {

    if(Auth::user()->type == 'player'){
        return redirect()->route('select.game');
    }

    $fight = [];
    $event = \App\Models\Event::find($id);
    $settings = \App\Models\Setting::find(1);
    $playerDeduction = $settings->player_deduction;
    $betOne = 0;
    $betTwo = 0;
    $betFive = 0;
    $betTen = 0;
    $betTwenty = 0;
    $betForty = 0;
    $myBetOne = 0;
    $myBetTwo = 0;
    $myBetFive = 0;
    $myBetTen = 0;
    $myBetTwenty = 0;
    $myBetForty = 0;
    if($event){
        $fight = \App\Models\Fight::find($event->active_fight);

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

        $wins = Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();

        $allBets = DB::table('bets as b')
                    ->join('users as u','b.user_id','=','u.id')
                    ->where('b.fight_id',$fight->id)
                    ->select('b.amount','b.bet','b.id','u.email','u.name','u.username')
                    ->orderBy('id','DESC')->get();
    }else{
        $fight = collect($fight);
    }

    return view('roulette.declare',compact('event','fight','betOne','betTwo','betFive','betTen','betTwenty','betForty','wins','myBetOne','myBetTwo','myBetFive','myBetTen','myBetTwenty','myBetForty','allBets'));

})->name('show.event.rg');

Route::post('/declare-winner-rg',[RGController::class,'declareWinnerRG'])->name('declare.rgwinner');
Route::post('/place-rg-ghost-bet',[RGController::class,'placeRGGhostBet'])->name('rgplace.ghost');

?>
