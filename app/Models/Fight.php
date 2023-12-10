<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    use HasFactory;

    protected $fillable = [
        'ghost_bet_wala',
        'ghost_bet_meron',
        'ghost_bet_blue',
        'ghost_bet_grey',
        'ghost_bet_red',
        'ghost_bet_yellow',
        'ghost_bet_white',
        'ghost_bet_pink'
    ];
}
