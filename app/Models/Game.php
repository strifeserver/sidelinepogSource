<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'banner',
        'status',
        'plasada',
        'display_name',
        'multiplier1',
        'multiplier2',
        'multiplier3',
        'jackpot',
        'type'
    ];
}
