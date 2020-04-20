<?php

namespace App\Http\Middleware;

use App\Constants;
use App\User;
use Closure;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->header('api_token');
        $authUser = User::where(['api_token' => $token])->first();
        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ]);
        } elseif ($authUser->status == Constants::INACITVE){
            return response()->json([
                'success' => false,
                'message' => 'User is inactive'
            ]);
        }

        return $next($request);
    }
}
