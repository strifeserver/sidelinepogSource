<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plasada = new Setting();
        $plasada->setting_name = 'plasada';
        $plasada->value = 7;
        $plasada->save();

        $commi = new Setting();
        $commi->setting_name = 'operator_commission';
        $commi->value = 7;
        $commi->save();

        $deduction = new Setting();
        $deduction->setting_name = 'player_deduction';
        $deduction->value = $commi->value+$plasada->value;
        $deduction->save();

        $deduction = new Setting();
        $deduction->setting_name = 'multiplier';
        $deduction->value = 1;
        $deduction->save();

    }
}
