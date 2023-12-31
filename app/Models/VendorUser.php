<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class VendorUser extends Authenticatable
{
    use HasFactory,HasApiTokens , Notifiable;

    protected $fillable = ['name' , 'email' , 'password'];

    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
