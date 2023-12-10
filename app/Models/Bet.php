<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'fight_id',
        'user_id',
        'op_id',
        'subop_id',
        'ma_id',
        'agent_id',
        'silver_id',
        'amount',
        'amount_won',
        'plasada',
        'operator_commission',
        'sub_operator_commission',
        'master_agent_commission',
        'gold_agent_commission',
        'silver_agent_commission',
        'bet',
        'result'
    ];
}
