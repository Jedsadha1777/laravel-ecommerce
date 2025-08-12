<?php

namespace App\Domains\Shop\User\Models;

use App\Traits\UsesUuid;
use App\Domains\Shop\Order\Models\Order;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UsesUuid;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}