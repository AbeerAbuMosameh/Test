<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Model
{
    use HasFactory , HasApiTokens , Notifiable;
    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
