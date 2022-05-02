<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;


class Maxim extends Model
{
  use HasFactory;
  use SoftDeletes;
  /**
   * @var array
   */
  protected $fillable = [
    'maxim',
    'author',
  ];
}
