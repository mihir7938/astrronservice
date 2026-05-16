<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;

class UsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $valid_request = false;
        $url = url('/?url='.$request->url());
        if (auth()->check() && auth()->user()->role_id == Role::USER_ROLE_ID) {
            $valid_request = true;
        }

        if ($valid_request) {
            return $next($request);
        } else {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'You are not allowed to perform this action.'], 403);
            }
            $request->session()->put('message', 'You are not allowed to access page this or your session has expired.');
            $request->session()->put('alert-type', 'alert-warning');

            return redirect($url);
        }
    }
}