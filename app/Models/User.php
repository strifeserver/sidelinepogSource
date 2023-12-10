<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'username',
        'contact_number',
        'password',
        'status',
        'type',
        'forgot_token',
        'referral_code',
        'referred_by',
        'created_by',
        'commission',
        'referrer_type'
    ];

    public function wallet(){
        return $this->hasOne('App\Models\Wallet');
    }
}
