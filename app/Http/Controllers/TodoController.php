<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
  public function index()
  {
    return Todo::orderBy('updated_at', 'desc')->get();
  }

  public function store(Request $request)
  {
    return Todo::create($request->all());
  }

  public function destroy(Todo $id)
  {
    return $id->delete();
  }

  public function update(Request $request, Todo $id)
  {
    $id->update($request->all());
    return $id;
  }
}
