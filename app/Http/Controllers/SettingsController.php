<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;

class SettingsController extends Controller
{
    public function saveSettings(Request $request){
        if(Setting::find($request['id'])->update(['value' => $request['value']])){

            $op = Setting::where('setting_name','operator_commission')->first();
            $playerDeduction = Setting::where('setting_name','player_deduction')->first();
            $plasada = $playerDeduction->value - $op->value;
            $set = Setting::find($request['id']);
            if($set->setting_name == 'operator_commission'){
                $sett = User::where('type','operator')->update(['commission' => $set->value,'plasada'=>$plasada]);
                return response($sett);
            }

            return response()->json([
                'msg' => 'Successfully saved changes!'
            ],200);
        }

        return response()->json([
            'msg' => 'Internal Server error!'
        ],500);

    }
}
