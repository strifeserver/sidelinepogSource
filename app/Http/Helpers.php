<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Fight;
use App\Models\Bet;
use App\Models\Load;
use App\Models\Withdraw;
use App\Models\IpAddress;

if (! function_exists('checkRole')) {
    function checkRole($role){
        $roles = ['master-agent','silver-agent','gold-agent','declarator','admin','loader','sub-operator','operator','super-admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canLoad')) {
    function canLoad($role){
        $roles = ['master-agent','agent','loader','admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canCreateEvent')) {
    function canCreateEvent($role){
        $roles = ['declarator','admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canViewMasterAgents')) {
    function canViewMasterAgents($role){
        $roles = ['loader','admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canViewOperators')) {
    function canViewOperators($role){
        $roles = ['loader','admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canViewAgents')) {
    function canViewAgents($role){
        $roles = ['loader','admin','master-agent'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('canViewWallet')) {
    function canViewWallet($role){
        $roles = ['admin','master-agent','agent','player','booster'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('notAPlayer')) {
    function notAPlayer($role){
        $roles = ['admin','master-agent','agent','loader','declarator'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('adminOnly')) {
    function adminOnly($role){
        $roles = ['admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}

if (! function_exists('agentOnly')) {
    function agentOnly($role){
        $roles = ['silver-agent','master-agent','gold-agent','operator','sub-operator','admin'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}


if (! function_exists('canWithdraw')) {
    function canWithdraw($role){
        $roles = ['agent','master-agent','loader'];
        if(in_array($role,$roles)){
            return true;
        }
        return false;
    }
}



if (! function_exists('removeUnderscore')) {
    function removeUnderscore($text){
        return strtoupper(str_replace("_", " ", $text));
    }
}

if (! function_exists('calculateOdds')) {
    function calculateOdds($meron,$wala,$percent){
        //$meron = total MERON BETS
        //$wala = total WALA BETS
        //$percent = 7%

        $combined = $meron+$wala;

        if($meron == $wala){
            $lessPercentage = $combined-($combined*($percent)/100);
        }else{
            $lessPercentage = ($combined-($combined*($percent)/100));
        }

        $walaOdds = 0;
        $meronOdds = 0;

        if($meron > 0){
            $meronOdds = ($lessPercentage/$meron)*100;
        }

        if($wala > 0){
            $walaOdds = ($lessPercentage/$wala)*100;
        }

        $odds = ['meron' => bcdiv($meronOdds,1,2),'wala'=>bcdiv($walaOdds,1,2)];
        return $odds;
    }
}


if (! function_exists('calculateAmountPercent')) {
    function calculateAmountPercent($odds,$bet){
        return ($odds/100)*$bet;
    }
}

if (! function_exists('computeAgentCommission')) {
    function computeAgentCommission($agentPercent,$masterAgentPercent,$bet,$playerID,$forType){
        $player = User::find($playerID);
        $agentOrMasterAgent = User::find($player->referred_by);
        $amount = 0;

        if($agentOrMasterAgent->type == 'agent'){

            if($forType == 'agent'){
                $amount = ($agentPercent/100)*$bet;
            }

            if($forType == 'master-agent'){
                $maPercent = $masterAgentPercent-$agentPercent;
                $amount = ($maPercent/100)*$bet;
            }

        }

        if($agentOrMasterAgent->type == 'master-agent'){
            $amount = ($masterAgentPercent/100)*$bet;
        }

        return $amount;
    }
}

if (! function_exists('getSubUser')) {
    function getSubUser($userType){
        switch($userType){
            case "super-admin":
                return 'operator';
                break;
            case "admin":
                return 'operator';
                break;
            case "operator":
                return 'sub-operator';
                break;
            case "sub-operator":
                return 'master-agent';
                break;
            case "master-agent":
                return 'gold-agent';
                break;
            case "gold-agent":
                return 'silver-agent';
                break;
            default:
                return "player";
        }
    }
}

if (! function_exists('getUplineCommission')) {
    function getUplineCommission($downlineId){
        $user = \App\Models\User::find($downlineId);
        $upline =\App\Models\User::find($user->referred_by);
        $data = [
            'user_id' => $upline->id,
            'commission' => $upline->commission,
            'plasada' => $upline->plasada,
            'type' => $upline->type
        ];
        return $data;
    }
}


if(! function_exists('noDupes')){
    function noDupes(array $input_array) {
        return count($input_array) === count(array_flip($input_array));
    }
}


if(! function_exists('findValue')){
    function findValue($string,$array){
        if($array == 'meron' || $array == 'wala'){
            return true;
        }else{
            $array = explode(',',$array,2);
            foreach($array as $arr){
                if($arr == $string){
                    return true;
                }
            }
        }

        return false;
    }
}

if(! function_exists('getEventDataFromBetID')){
    function getEventDataFromBetID($id) {
        $bet = Bet::find($id);
        $event = Event::withTrashed()->find($bet->event_id);
        $fight = Fight::find($bet->fight_id);
        $data = [
            'event' => $event,
            'fight' => $fight,
            'bet' => $bet
        ];
        return $data;
    }
}

if(! function_exists('findLoadedBy')){
    function findLoadedBy($loadID) {
        $load = Load::find($loadID);
        $loadedBy = User::find($load->requested_to);
        return $loadedBy;
    }

}

if(! function_exists('findWithdrawnBy')){
    function findWithdrawnBy($loadID) {
        $widthdraw = Withdraw::find($loadID);
        $withdrawnBy = User::find($widthdraw->requested_to);
        return $withdrawnBy;
    }
}

if(! function_exists('getClientIP')){
    function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}

if(! function_exists('saveIPAddress')){
    function saveIPAddress() {
        $ip = getClientIP();
        $ipAdd = IpAddress::where('ip_address',$ip)->where('user_id',Auth::id())->get();

        if(count($ipAdd) == 0){
            $ipAddress = new IpAddress();
            $ipAddress->user_id = Auth::id();
            $ipAddress->ip_address = $ip;
            $ipAddress->save();
        }
    }
}

?>
