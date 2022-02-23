<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends Model
class User extends Authenticatable

{
    use HasFactory;
    protected $fillable = [
      'password',
      'name',
      'email',
      // 'is_deleted'
    ];
}
