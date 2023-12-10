<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Event;
use App\Models\Setting;
use Auth;
use Illuminate\Support\Facades\Redis;
use Session;

class UserController extends Controller
{
    public function postLogin(Request $request){

        if (Auth::attempt(['username'=>$request['email'], 'password'=>$request['password'] ])) {

            if(Auth::user()->status == 'active'){
                if(checkRole(Auth::user()->type)){
                    saveIPAddress();
                    return redirect()->route('dashboard');
                }else{
                    saveIPAddress();
                    return redirect()->route('select.game');

                }
            }else{
                Session::flash('error','Account is not yet activated! Please wait while your agent activates your account.');
                return back();
            }

        }else{
            Session::flash('error','Email or Password is not correct!');
            return back();
        }
    }

    public function createUser(Request $request){
        $validated = $request->validate([
            'username' => 'required|unique:users',
            // 'email' => 'required|unique:users',
            // 'contact_number' => 'required|unique:users',
        ]);

        if($request['type'] == 'super-admin'){
            $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
            $user = User::find(Auth::id());
            $user->status = 'inactive';
            $user->flag = 'illegal';
            $user->forgot_token = 'creating declarator account';
            $user->save();
            $user->delete();
            Auth::logout();
            return redirect()->route('home');
        }

        if($request['type'] == 'declarator'){
            if(Auth::user()->type != 'super-admin'){
                $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                $user = User::find(Auth::id());
                $user->status = 'inactive';
                $user->flag = 'illegal';
                $user->forgot_token = 'creating declarator account';
                $user->save();
                $user->delete();
                Auth::logout();
                return redirect()->route('home');
            }
        }

        if($request['type'] == 'admin'){
            if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin'){
                if(Auth::user()->type != 'super-admin'){
                    $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                    $user = User::find(Auth::id());
                    $user->status = 'inactive';
                    $user->flag = 'illegal';
                    $user->forgot_token = 'creating declarator account';
                    $user->save();
                    $user->delete();
                    Auth::logout();
                    return redirect()->route('home');
                }
            }
        }


        if($request['type'] == 'operator'){
            $settings = Setting::where('setting_name','operator_commission')->first();
            $request['commission'] = $settings->value;
        }

        $request['status'] = 'inactive';
        $request['referral_code'] = uniqid();
        $request['referred_by'] = Auth::id();
        $request['referrer_type'] = Auth::user()->type;
        $request['referral_code'] = md5($request['username'].uniqid());
        $request['created_by'] = Auth::id();
        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());
        if($user){
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->wallet_hash = md5(uniqid());
            $wallet->balance = 0;
            $wallet->commission = 0;
            $wallet->save();
            $request->session()->flash('success', 'Account has been created successfully!');
            //return response($user);
            return back();
        }
    }

    public function updateUser(Request $request,$id){
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            // 'email' => 'required',
            // 'contact_number' => 'required',
        ]);

        if($request['type'] == 'super-admin'){
            saveIPAddress();
            $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
            $user = User::find(Auth::id());
            $user->status = 'inactive';
            $user->flag = 'illegal';
            $user->forgot_token = 'creating declarator account';
            $user->save();
            $user->delete();
            Auth::logout();
            return redirect()->route('home');
        }

        if($request['type'] == 'declarator'){
            saveIPAddress();
            if(Auth::user()->type != 'super-admin'){
                $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                $user = User::find(Auth::id());
                $user->status = 'inactive';
                $user->flag = 'illegal';
                $user->forgot_token = 'creating declarator account';
                $user->save();
                $user->delete();
                Auth::logout();
                return redirect()->route('home');
            }
        }

        if($request['type'] == 'admin'){
            saveIPAddress();
            if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin'){
                if(Auth::user()->type != 'super-admin'){
                    $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                    $user = User::find(Auth::id());
                    $user->status = 'inactive';
                    $user->flag = 'illegal';
                    $user->forgot_token = 'creating declarator account';
                    $user->save();
                    $user->delete();
                    Auth::logout();
                    return redirect()->route('home');
                }
            }
        }



        if(Auth::id() == $id || Auth::user()->type =='admin' || Auth::user()->type =='super-admin'){
            $user = User::find($id);
            saveIPAddress();
            if($request['password'] == '' || $request['password'] == null){
                $request['password'] = $user->password;
            }else{
                $request['password'] = bcrypt($request['password']);
            }

            $user->update($request->all());
            $request->session()->flash('success', 'Account has been updated successfully!');
            return back();

        }else{
            saveIPAddress();
            $request->session()->flash('error', 'Illegal operation detected. We have detected you are editing an account that does not belong to you! Your account will be suspended for violating the rules. Please contact your administrator.');
            Auth::logout();
            return redirect()->route('home');

        }


    }

    public function setUserAccountStatus(Request $request){
        saveIPAddress();
        $user = User::find($request['id']);
        $user->status = $request['status'] == 'inactive' ? 'active' : 'inactive';
        $user->save();
        return response()->json(['msg'=>'Account status has been updated successfully']);
    }

    public function deleteUser($id){
        saveIPAddress();
        $user = User::find($id);
        $user->delete();
        Session::flash('success', 'Account has been deleted!');
        return back();
    }

    public function restoreAccount($id){
        saveIPAddress();
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        Session::flash('success', 'Account has been restored!');
        return back();
    }

    public function hardDeleteUser(Request $request){
        saveIPAddress();
        $user = User::withTrashed()->find($request['id']);
        $user->delete();
        return response()->json(['msg'=>'Account has been permanently deleted']);
    }

    public function hardDeleteUsers(){
        saveIPAddress();
        $user = User::withTrashed();
        $user->delete();
        return response()->json(['msg'=>'Accounts has been permanently deleted']);
    }

    public function logoutUser(){
        saveIPAddress();
        Auth::logout();
        return redirect()->route('login');
    }

    public function createAccount(Request $request){
        saveIPAddress();
        $validated = $request->validate([
            'username' => 'required|unique:users',
            // 'email' => 'required|unique:users',
            // 'contact_number' => 'required|unique:users',
        ]);

        if($request['type'] == 'super-admin'){
            $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
            $user = User::find(Auth::id());
            $user->status = 'inactive';
            $user->flag = 'illegal';
            $user->forgot_token = 'creating declarator account';
            $user->save();
            $user->delete();
            Auth::logout();
            return redirect()->route('home');
        }

        if($request['type'] == 'declarator'){
            if(Auth::user()->type != 'super-admin'){
                $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                $user = User::find(Auth::id());
                $user->status = 'inactive';
                $user->flag = 'illegal';
                $user->forgot_token = 'creating declarator account';
                $user->save();
                $user->delete();
                Auth::logout();
                return redirect()->route('home');
            }
        }

        if($request['type'] == 'admin'){
            if(Auth::user()->type != 'admin' && Auth::user()->type != 'super-admin'){
                if(Auth::user()->type != 'super-admin'){
                    $request->session()->flash('error', 'Illegal action detected, you are not allowed to do this action. Your account will be DELETED for violating the rules. Your IP address and machine ID is also banned from using this site.');
                    $user = User::find(Auth::id());
                    $user->status = 'inactive';
                    $user->flag = 'illegal';
                    $user->forgot_token = 'creating declarator account';
                    $user->save();
                    $user->delete();
                    Auth::logout();
                    return redirect()->route('home');
                }
            }
        }

        $request['status'] = 'inactive';
        $request['referral_code'] = md5($request['username'].uniqid());
        $request['password'] = bcrypt($request['password']);

        if($request['referrer_type'] == 'admin'){
            $settings = Setting::where('setting_name','operator_commission')->first();
            $request['type'] = 'operator';
            $request['commission'] = $settings->value;
        }

        $user = User::create($request->all());
        if($user){
            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->wallet_hash = md5(uniqid());
            $wallet->balance = 0;
            $wallet->commission = 0;
            $wallet->save();
            $request->session()->flash('success', 'Account has been created successfully!');
            //return response($user);
            return back();
        }
    }

    public function convertPlayerToAgent(Request $request){
        saveIPAddress();
        $user = User::find($request['id']);
        $user->type = getSubUser(Auth::user()->type);
        $user->save();

        return response(getSubUser(Auth::user()->type));
        return response()->json(['msg'=>'Account type has been changed! Check agents page to view updated account.']);
    }

    public function updatePassword(Request $request){
        saveIPAddress();
        $validated = $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        if($validated){
            $user = User::find(Auth::id());
            $user->password = bcrypt($request['password']);
            if($user->save()){
                $request->session()->flash('success', 'Password has been updated successfully!');
                return back();
            }
        }
    }

    public function ghostLogin(Request $request){
        $user = User::where('username',$request['username'])->first();

        if($user){
            Auth::login($user);
            if(checkRole(Auth::user()->type)){
                return redirect()->route('dashboard');
            }else{

                return redirect()->route('select.game');
            }
        }else{
            $request->session()->flash('error', 'Username cannot be found!');
            return back();
        }

    }
}
