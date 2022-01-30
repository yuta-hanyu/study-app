<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
  protected $fillable = [
    'title',
    'content',
    'state',
    'book_mark',
    'is_deleted'
  ];
}
