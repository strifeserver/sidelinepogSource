<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Load;
use App\Models\Withdraw;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Setting;

use Session;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class WalletController extends Controller
{
    // public function requestLoad(Request $request){
    //     $load = new Load();

    //     if ($request->file('screenshot')) {
    //         $file = $request->file('screenshot');
    //         //set a unique file name
    //         $filename = uniqid().'.'.$file->getClientOriginalExtension();
    //         if ($file->move('img/receipts/', $filename)) {
    //             $load->screenshot = 'img/receipts/'.$filename;
    //         }
    //     }
    //     if(Auth::user()->type == 'agent' || Auth::user()->type == 'player'){
    //         $request['requested_to'] = Auth::user()->referred_by;
    //     }
    //     $load->requested_by = Auth::id();
    //     $load->requested_to = $request['requested_to'];
    //     $load->amount = $request['amount'];
    //     $load->reference_number = $request['reference_number'];
    //     if($load->save()){
    //         $request->session()->flash('success', 'Load request has been submitted!');
    //         return back();
    //     }
    // }

    // public function approveLoadRequest($id){
    //     $load = Load::find($id);

    //     //deduct amount from loader
    //     if(Auth::user()->type == 'loader'){
    //         $wallet = Wallet::where('user_id',Auth::id())->first();
    //     }else{
    //         $wallet = Wallet::where('user_id',$load->requested_to)->first();
    //     }


    //     if($wallet->balance < $load->amount){
    //         Session::flash('error', 'Insufficient wallet balance! Please cash in to your wallet first.');
    //         return back();
    //     }else{
    //         $wallet->balance = $wallet->balance-$load->amount;
    //         $wallet->save();


    //         // deduct load transaction from loader/agent
    //         $tx = new Transaction();
    //         $tx->user_id = $wallet->user_id;
    //         $tx->amount = $load->amount;
    //         $tx->ending_balance = $wallet->balance;
    //         $tx->type = 'load';
    //         $tx->direction = 'out';
    //         $tx->remarks = 'approve load request';
    //         $tx->save();
    //         $deductLoadFromID = $tx->id;


    //         //add amount to buyer
    //         $wallet = Wallet::where('user_id',$load->requested_by)->first();
    //         $wallet->balance = $wallet->balance+$load->amount;
    //         $wallet->save();

    //         //add load transaction to player
    //         $tx = new Transaction();
    //         $tx->user_id = $wallet->user_id;
    //         $tx->load_id = $load->id;
    //         $tx->amount = $load->amount;
    //         $tx->ending_balance = $wallet->balance;
    //         $tx->type = 'load';
    //         $tx->direction = 'in';
    //         $tx->remarks = 'load request approved';
    //         $tx->save();


    //         $load->status = 'completed';
    //         if($load->save()){
    //             $tx = Transaction::find($deductLoadFromID);
    //             $tx->load_id = $load->id;
    //             $tx->save();

    //             Session::flash('success', 'Load request has been approved!');
    //             return back();
    //         }
    //     }


    // }

    // public function requestWithdrawCommission(Request $request){
    //     $withdraw = new Withdraw();
    //     $withdraw->requested_to = Auth::user()->referred_by;
    //     $withdraw->type = 'commission';
    //     $withdraw->requested_by = Auth::id();
    //     $withdraw->withdraw_method = $request['withdraw_method'];
    //     $withdraw->account_number = $request['account_number'];
    //     $withdraw->account_name = $request['account_name'];
    //     $withdraw->amount = $request['amount'];
    //     //add load transaction to player
    //     if($withdraw->save()){
    //         $wallet = Wallet::where('user_id',Auth::id())->first();
    //         $wallet->commission = $wallet->commission - $request['amount'];
    //         $wallet->save();

    //         //deduct commission amount to wallet
    //         $tx = new Transaction();
    //         $tx->user_id = $wallet->user_id;
    //         $tx->withdraw_id = $withdraw->id;
    //         $tx->amount = $withdraw->amount;
    //         $tx->ending_balance = $wallet->commission;
    //         $tx->type = 'withdraw-commission';
    //         $tx->direction = 'out';
    //         $tx->remarks = 'commission withdraw request';
    //         $tx->save();

    //         $request->session()->flash('success', 'Commission withdraw request has been submitted!');
    //         return back();
    //     }
    // }

    public function requestWithdrawCredits(Request $request){
        saveIPAddress();
        if($request['amount'] < 0){
            return back();
        }
        if(Auth::user()->flag == 'illegal'){
            $request->session()->flash('error','Your account is flagged. Transaction denied, please contact your administrator.');
        }
        $withdraw = new Withdraw();
        $withdraw->requested_to = Auth::user()->referred_by;
        $withdraw->type = 'credits';
        $withdraw->requested_by = Auth::id();
        $withdraw->withdraw_method = $request['withdraw_method'];
        $withdraw->account_number = $request['account_number'];
        $withdraw->account_name = $request['account_name'];
        $withdraw->amount = $request['amount'];
        $withdraw->status = 'pending';

        if($withdraw->save()){
            $wallet = Wallet::where('user_id',Auth::id())->first();
            $wallet->balance = $wallet->balance - $request['amount'];
            $wallet->save();
            //deduct credits amount to wallet
            $tx = new Transaction();
            $tx->user_id = $wallet->user_id;
            $tx->withdraw_id = $withdraw->id;
            $tx->amount = $withdraw->amount;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'withdraw-credits';
            $tx->direction = 'out';
            $tx->remarks = 'credits withdraw request';
            $tx->save();

            // comment this code block to allow withdraw approval and the withdraw status
            $wallet = Wallet::where('user_id',Auth::user()->referred_by)->first();
            $wallet->balance = $wallet->balance + $request['amount'];
            $wallet->save();

            $tx = new Transaction(); //add credits amount to wallet
            $tx->user_id = $wallet->user_id;
            $tx->withdraw_id = $withdraw->id;
            $tx->amount = $withdraw->amount;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'withdraw-credits';
            $tx->direction = 'in';
            $tx->remarks = 'credits withdraw request from '.Auth::user()->username;
            $tx->save();
            //up to here

            $request->session()->flash('success', 'Credits withdraw request has been submitted!');
            return back();
        }
    }

    public function withdrawCommission(Request $request){
        saveIPAddress();
        if($request['amount'] < 0){
            return back();
        }

        if(Auth::user()->flag == 'illegal'){
            $request->session()->flash('error','Your account is flagged. Transaction denied, please contact your administrator.');
        }

        $wallet = Wallet::where('user_id',$request['user_id'])->first();
        $user = User::find($request['user_id']);

        if($request['amount'] > $wallet->commission){
            $request->session()->flash('error', 'Requested amount exceeds available commission! Unable to process withdrawal');
            return back();
        }

        if($user->flag == 'illegal'){
            $request->session()->flash('error', 'This user account has been flagged and you cannot cashout the amount. Please contact system administrator.');
            return back();
        }

        if($user->referred_by == Auth::id() || Auth::user()->type =='admin' || Auth::user()->type =='super-admin'){
            $wallet->commission = $wallet->commission - $request['amount'];
            $wallet->save();
            //deduct credits amount to wallet

            $withdraw = new Withdraw();
            $withdraw->requested_to = Auth::id();
            $withdraw->type = 'commission';
            $withdraw->requested_by = $request['user_id'];
            $withdraw->amount = $request['amount'];
            $withdraw->processed_by =  Auth::id();
            $withdraw->status =  'completed';
            $withdraw->save();

            $tx = new Transaction();
            $tx->user_id = $wallet->user_id;
            $tx->withdraw_id = $withdraw->id;
            $tx->amount = $request['amount'];
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'commission-out';
            $tx->direction = 'out';
            $tx->remarks = $request['amount']. " commission converted to points and deducted from ". $user->username . " by ".Auth::user()->username;
            $tx->user_notes = $request['remarks'];
            $tx->save();

            $wallet = Wallet::where('user_id',Auth::id())->first();
            $wallet->balance = $wallet->balance + $request['amount'];
            $wallet->save();

            //return credits amount to wallet
            $tx = new Transaction();
            $tx->user_id = $wallet->user_id;
            $tx->withdraw_id = $withdraw->id;
            $tx->amount = $request['amount'];
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'commission-in-to-wallet';
            $tx->direction = 'in';
            $tx->remarks = $request['amount']. " commission converted to points and deducted from ". $user->username . " by ".Auth::user()->username;
            $tx->user_notes = $request['remarks'];
            $tx->save();

            $request->session()->flash('success', 'Commission has been deducted!');
            return back();

        }else{
            $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be suspended for violating the rules. Please contact your administrator');
            // $user = User::find(Auth::id());
            // $user->status = 'inactive';
            // $user->flag = 'illegal';
            // $user->forgot_token = 'Withdrawing amount from not direct downline';
            // $user->save();
            // Auth::logout();
            // return redirect()->route('home');
            return back();
        }

}

    public function returnCredits(Request $request){
            saveIPAddress();
            if($request['amount'] < 0){
                $request->session()->flash('error','Illegal action detected! You are attempting to withdraw a negative amount! Your account will be blocked. Please contact your administrator.');
                // $user = User::find(Auth::id());
                // $user->status = 'inactive';
                // $user->flag = 'illegal';
                // $user->save();
                // Auth::logout();
                // return redirect()->route('login');
                return back();
            }

            if(Auth::user()->flag == 'illegal'){
                $request->session()->flash('error','Your account is flagged. Transaction denied, please contact your administrator.');
            }

            $wallet = Wallet::where('user_id',$request['user_id'])->first();
            $user = User::find($request['user_id']);

            if($request['amount'] > $wallet->balance){
                $request->session()->flash('error', 'Requested amount exceeds wallet balance! Unable to process withdrawal');
                return back();
            }

            if($user->flag == 'illegal'){
                $request->session()->flash('error', 'This user account has been flagged and you cannot cashout the amount. Please contact system administrator.');
                return back();
            }

            if($user->referred_by == Auth::id() || Auth::user()->type =='admin' || Auth::user()->type =='super-admin'){
                $wallet->balance = $wallet->balance - $request['amount'];
                $wallet->save();
                //deduct credits amount to wallet

                $withdraw = new Withdraw();
                $withdraw->requested_to = Auth::id();
                $withdraw->type = 'credits';
                $withdraw->requested_by = $request['user_id'];
                $withdraw->amount = $request['amount'];
                $withdraw->processed_by =  Auth::id();
                $withdraw->status =  'completed';
                $withdraw->save();

                $tx = new Transaction();
                $tx->user_id = $wallet->user_id;
                $tx->withdraw_id = $withdraw->id;
                $tx->amount = $request['amount'];
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'refund';
                $tx->direction = 'out';
                $tx->remarks = $request['amount']. " load deducted from ". $user->username . " by ".Auth::user()->username;
                $tx->user_notes = $request['remarks'];
                $tx->save();

                $wallet = Wallet::where('user_id',Auth::id())->first();
                $wallet->balance = $wallet->balance + $request['amount'];
                $wallet->save();

                //return credits amount to wallet
                $tx = new Transaction();
                $tx->user_id = $wallet->user_id;
                $tx->withdraw_id = $withdraw->id;
                $tx->amount = $request['amount'];
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'refund';
                $tx->direction = 'in';
                $tx->remarks = $request['amount']. " load deducted from ". $user->username . " by ".Auth::user()->username;
                $tx->user_notes = $request['remarks'];
                $tx->save();

                $request->session()->flash('success', 'Funds has been deducted!');
                return back();

            }else{
                $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be suspended for violating the rules. Please contact your administrator');
                $user = User::find(Auth::id());
                $user->status = 'inactive';
                $user->flag = 'illegal';
                $user->save();
                Auth::logout();
                return redirect()->route('home');
            }

    }

    // public function approveCommissionWithdraw($id){
    //     $withdraw = Withdraw::find($id);
    //     $withdraw->status = 'completed';
    //     $withdraw->processed_by = Auth::id();

    //     if($withdraw->save()){
    //         //add amount to loader
    //         $loader = Wallet::where('user_id',Auth::id())->first();
    //         $loader->balance = $loader->balance+$withdraw->amount;
    //         $loader->save();

    //         // add commission amount to loader wallet
    //         $tx = new Transaction();
    //         $tx->user_id = $loader->user_id;
    //         $tx->withdraw_id = $withdraw->id;
    //         $tx->amount = $withdraw->amount;
    //         $tx->ending_balance = $loader->balance;
    //         $tx->type = 'withdraw-commission';
    //         $tx->direction = 'in';
    //         $tx->remarks = 'commission withdraw approval';
    //         $tx->save();
    //         Session::flash('success', 'Withdraw request has been completed!');
    //         return back();
    //     }
    // }

    // public function approveCreditsWithdrawal($id){
    //     $withdraw = Withdraw::find($id);
    //     $withdraw->status = 'completed';
    //     $withdraw->processed_by = Auth::id();
    //     if($withdraw->save()){

    //         $loader = Wallet::where('user_id',Auth::id())->first();
    //         $loader->balance = $loader->balance+$withdraw->amount;
    //         $loader->save();

    //         //add credits amount to loader wallet
    //         $tx = new Transaction();
    //         $tx->user_id = $loader->user_id;
    //         $tx->withdraw_id = $withdraw->id;
    //         $tx->amount = $withdraw->amount;
    //         $tx->ending_balance = $loader->balance;
    //         $tx->type = 'withdraw-credits';
    //         $tx->direction = 'in';
    //         $tx->remarks = 'credits withdraw request';
    //         $tx->save();

    //         Session::flash('success', 'Withdraw request has been completed!');
    //         return back();
    //     }
    // }

    // public function passWithdrawalToMA($id){
    //     $withdraw = Withdraw::find($id);
    //     $withdraw->status = 'master_agent_approval';
    //     $withdraw->processed_by = Auth::id();
    //     $withdraw->requested_to = Auth::user()->referred_by;

    //     if($withdraw->save()){
    //         Session::flash('success', 'Withdraw request has been completed!');
    //         return back();
    //     }
    // }

    public function convertCommission(Request $request){
        saveIPAddress();
        $wallet = Wallet::where('user_id',Auth::id())->first();

        if(Auth::user()->flag == 'illegal'){
            $request->session()->flash('error','Your account is flagged. Transaction denied, please contact your administrator.');
        }

        if($request['amount'] < 0){
            $request->session()->flash('error', 'Illegal action detected, converting negative points not allowed. Your account will be suspended for violating the rules. Please contact your administrator');
            $user = User::find(Auth::id());
            $user->status = 'inactive';
            $user->flag = 'illegal';
            $user->save();
            Auth::logout();
            return redirect()->route('login');
        }

        if($request['amount'] > $wallet->commission){
            Session::flash('error', 'Commission convert request cannot be completed! Amount exceeds current commission.');
            return back();
        }

        if($request['amount'] < 500){
            $request->session()->flash('error','Minimum of 500 for self conversion. Please ask your upline to convert for less than 500 commission');
        }

        $wallet->commission = $wallet->commission-$request['amount'];
        $wallet->balance = $wallet->balance+$request['amount'];
        if($wallet->save()){
            $load = new Load();
            $load->requested_by = Auth::id();
            $load->requested_to = Auth::id();
            $load->amount = $request['amount'];
            $load->status = 'completed';
            $load->reference_number = md5(uniqid());
            $load->screenshot = date('Y-m-d H:i:s');
            $load->save();

            // add credits to wallet
            $tx = new Transaction();
            $tx->user_id = $load->requested_by;
            $tx->load_id = $load->id;
            $tx->amount = $load->amount;
            $tx->ending_balance = $wallet->balance;
            $tx->type = 'load';
            $tx->direction = 'in';
            $tx->remarks = $request['amount']. " commission converted to load by ".Auth::user()->username;
            $tx->save();

            $request->session()->flash('success','Commission has been converted to credits successfully!');
            return back();
        }
    }

    public function addWalletBalance(Request $request){
        saveIPAddress();
        $myWallet = Wallet::where('user_id',Auth::id())->first();
        $receiver = User::find($request['user_id']);

        if(Auth::user()->flag == 'illegal'){
            $request->session()->flash('error','Your account is flagged. Transaction denied, please contact your administrator.');
        }

        if($request['amount'] < 0){
            $request->session()->flash('error','Illegal action detected! You are attempting to deposit a negative amount! Your account will be blocked. Please contact your administrator.');
            $user = User::find(Auth::id());
            $user->status = 'inactive';
            $user->flag = 'illegal';
            $user->save();
            Auth::logout();
            return redirect()->route('login');
        }

        if($myWallet->balance < $request['amount']){
            $request->session()->flash('error','Insufficient wallet balance!');
            return back();
        }

        if($receiver->referred_by == Auth::id() || Auth::user()->type =='admin' || Auth::user()->type =='super-admin'){
            $wallet = Wallet::where('user_id',$request['user_id'])->first();
            $wallet->balance = $wallet->balance+$request['amount'];
            if($wallet->save()){
                $load = new Load();
                $load->requested_by = $request['user_id'];
                $load->requested_to = Auth::id();
                $load->amount = $request['amount'];
                $load->status = 'completed';
                $load->reference_number = md5(uniqid());
                $load->screenshot = date('Y-m-d H:i:s');
                $load->save();

                // add credits to wallet
                $tx = new Transaction();
                $tx->user_id = $load->requested_by;
                $tx->load_id = $load->id;
                $tx->amount = $load->amount;
                $tx->ending_balance = $wallet->balance;
                $tx->type = 'load';
                $tx->direction = 'in';
                $tx->remarks = $request['amount']. " load credited from ".Auth::user()->username." to ". $receiver->username;
                $tx->user_notes = $request['remarks'];
                $tx->save();

                $myWallet->balance = $myWallet->balance-$request['amount'];
                $myWallet->save();
                //deduct from loader wallet
                $tx = new Transaction();
                $tx->user_id = Auth::id();
                $tx->load_id = $load->id;
                $tx->amount = $load->amount;
                $tx->ending_balance = $myWallet->balance;
                $tx->type = 'load';
                $tx->direction = 'out';
                $tx->remarks = $request['amount']." load credited from ".Auth::user()->username." to ". $receiver->username;
                $tx->user_notes = $request['remarks'];
                $tx->save();

                $request->session()->flash('success','Wallet has been loaded successfully!');
                return back();
            }
        }else{
            $request->session()->flash('error','Selected user is not your downline. Unable to proceed transaction.');
            return back();
        }



    }

    public function setCommission(Request $request){
        saveIPAddress();
        $pd = Setting::where('setting_name','player_deduction')->first();
        $user = User::find($request['user_id']);

        if(Auth::user()->type =='admin' || Auth::user()->type =='super-admin'){

            if($request['commission'] >= $pd->value){
                $request->session()->flash('error','Commission percentage exceeds maximum allowed commission! Please adjust in settings page first.');
                return back();
            }
            $user->commission = $request['commission'];
            if($user->save()){
                $request->session()->flash('success','Commission (%) has been updated successfully!');
                return back();
            }

        }else{
            if($request['commission'] >= Auth::user()->commission){
                $request->session()->flash('error','Commission percent must be less than your current commission!');
                return back();
            }else{
                if($user->referred_by == Auth::id()){
                    $user->commission = $request['commission'];
                    if($user->save()){
                        $request->session()->flash('success','Commission (%) has been updated successfully!');
                        return back();
                    }
                }else{
                    $request->session()->flash('error','Illegal action detected! You are attempting to set a commission of another agent that is NOT your downline! Your account will be blocked. Please contact your administrator.');
                    $user = User::find(Auth::id());
                    $user->status = 'inactive';
                    $user->flag = 'illegal';
                    $user->save();
                    Auth::logout();
                    return redirect()->route('login');
                }

            }
        }



    }

    public function addSystemBalance(Request $request){
        saveIPAddress();
        if(Auth::user()->type != 'super-admin'){
            return redirect()->route('dashboard');
        }
        $myWallet = Wallet::where('user_id',Auth::id())->first();
        $myWallet->balance = $myWallet->balance+$request['amount'];
        if($myWallet->save()){
            $load = new Load();
            $load->requested_by = Auth::id();
            $load->requested_to = Auth::id();
            $load->amount = $request['amount'];
            $load->status = 'completed';
            $load->reference_number = md5(uniqid());
            $load->screenshot = date('Y-m-d H:i:s');
            $load->save();

            $tx = new Transaction();
            $tx->user_id = Auth::id();
            $tx->load_id = $load->id;
            $tx->amount = $load->amount;
            $tx->ending_balance = $myWallet->balance;
            $tx->type = 'load';
            $tx->direction = 'in';
            $tx->remarks = 'Add system credits processed by'. Auth::user()->username;
            $tx->save();

            $request->session()->flash('success','Successfully added balance to wallet!');
            return back();
        }
    }
}
