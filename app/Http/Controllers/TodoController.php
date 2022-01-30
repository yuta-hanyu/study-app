<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
  public function index()
  {
    $a = "a";
    return Todo::all();
  }
}
