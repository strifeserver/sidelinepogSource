<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Ismini Sandile";
        $user->email = "super-admin@gmail.com";
        $user->username = "superadmin";
        $user->contact_number = "09123456789";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "super-admin";
        $user->referral_code = md5("username".uniqid());
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Meshullam Theotimus";
        $user->email = "admin@gmail.com";
        $user->username = "admin1";
        $user->contact_number = "09123456789";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "admin";
        $user->referral_code = md5("username".uniqid());
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Gudrun Wera";
        $user->email = "loader@gmail.com";
        $user->username = "loader";
        $user->contact_number = "09246810121";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "loader";
        $user->referral_code = md5("username".uniqid());
        $user->created_by = 1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Rusul Shaylyn";
        $user->email = "sub-operator@gmail.com";
        $user->username = "sub-operator";
        $user->contact_number = "09246810121";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "sub-operator";
        $user->referral_code = md5("username".uniqid());
        $user->created_by = 1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Xandra Perica";
        $user->email = "declarator@gmail.com";
        $user->username = "declarator";
        $user->contact_number = "09369121416";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "declarator";
        $user->referral_code = md5("username".uniqid());
        $user->created_by = 1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();


        $user = new User();
        $user->name = "Hugo Babette";
        $user->email = "master_agent1@gmail.com";
        $user->username = "master_agent1";
        $user->contact_number = "09481216202";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "operator";
        $user->referral_code = md5("username".uniqid());
        $user->created_by = 1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Pandora Sigismund";
        $user->email = "master_agent2@gmail.com";
        $user->username = "master_agent2";
        $user->contact_number = "09057333839";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "master-agent";
        $user->referral_code = md5("username".uniqid());
        $user->created_by = 1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        // for($i=3;$i<=30;$i++){
        //     $user = new User();
        //     $user->name = "Master Agent".$i;
        //     $user->email = "master_agent".$i."@gmail.com";
        //     $user->username = "master_agent".$i;
        //     $user->contact_number = "09057333839";
        //     $user->password = bcrypt("123456");
        //     $user->status = "active";
        //     $user->type = "master_agent";
        //     $user->referral_code = md5("username".uniqid());
        //     $user->created_by = 1;
        //     $user->save();
        //     $wallet = new Wallet();
        //     $wallet->user_id = $user->id;
        //     $wallet->wallet_hash = md5(uniqid());
        //     $wallet->balance = 0;
        //     $wallet->commission = 0;
        //     $wallet->save();
        // }

        $user = new User();
        $user->name = "Cornelius Hesiod";
        $user->email = "agent1@gmail.com";
        $user->username = "agent1";
        $user->contact_number = "09489275616";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "gold-agent";
        $user->referral_code = md5("username".uniqid());
        $user->referred_by = 4+1;
        $user->created_by = 4+1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Hasna Herschel";
        $user->email = "agent2@gmail.com";
        $user->username = "agent2";
        $user->contact_number = "09175238633";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "gold-agent";
        $user->referral_code = md5("username".uniqid());
        $user->referred_by = 5+1;
        $user->created_by = 5+1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Cardo Dalisay";
        $user->email = "player1@gmail.com";
        $user->username = "player1";
        $user->contact_number = "09238014700";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "player";
        $user->referral_code = md5("username".uniqid());
        $user->referred_by = 5+1;
        $user->created_by = 5+1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Pedro Penduko";
        $user->email = "player2@gmail.com";
        $user->username = "player2";
        $user->contact_number = "09238014700";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "player";
        $user->referral_code = md5("username".uniqid());
        $user->referred_by = 7+1;
        $user->created_by = 7+1;
        $user->save();
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();

        $user = new User();
        $user->name = "Booster Account";
        $user->email = "booster@gmail.com";
        $user->username = "booster";
        $user->contact_number = "09238014700";
        $user->password = bcrypt("123456");
        $user->status = "active";
        $user->type = "booster";
        $user->referral_code = md5("username".uniqid());
        $user->save();

        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->wallet_hash = md5(uniqid());
        $wallet->balance = 0;
        $wallet->commission = 0;
        $wallet->save();
    }
}
