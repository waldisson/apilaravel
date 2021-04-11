<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable implements JWTSubject
{
   use HasFactory, Notifiable;

   protected $hidden = ['password'];

   public function getJWTIdentifier() {
       return $this->getKey();
   }

   public function getJWTCustomClaims() {
       return [];
   }
}
