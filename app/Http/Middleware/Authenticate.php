<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
      // $isError = true;
      // return response()->json(['isError' => $isError, 'errorMSG' => config('const.ERROR_MSG.EXPAIRED_SESSION.')]);
        if (! $request->expectsJson()) {
            return route('/login');
        }
    }
}
