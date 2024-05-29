<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Shuttle\PhpPack\Traits\ShuttlePhpPackTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate
{
    use ShuttlePhpPackTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request, Closure $next)
    // {
    //     $token = $request->header('Authorization');
    //     if (!$token) return response(["error" => "Unauthorized"], 401);
    //     try {
    //         $decodedToken = $this->tokenVerify($token);
    //         $request->setUserResolver(function () use ($decodedToken) {
    //             return $decodedToken["user"];
    //         });
    //     } catch (\Exception $e) {
    //         return response(["error" => "Unauthorized"], 401);
    //     }
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response(["error" => "Unauthorized"], 401);
        }

        try {
            $decodedToken = $this->tokenVerify($token);
            $user_phone = $decodedToken['user']->mobile ?? '';

            $isAdmin = User::query()
                ->where('phone', $user_phone)
                ->with(['role'])
                ->whereHas('role', function ($q) {
                    $q->where('name', 'admin');
                })
                ->exists();

            if ($isAdmin) {
                $request->setUserResolver(function () use ($decodedToken) {
                    return $decodedToken["user"];
                });
            } else {
                return response(["error" => "Unauthorized"], 401);
            }
        } catch (\Exception $e) {
            return response(["error" => "Unauthorized"], 401);
        }

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*')
            ->header('Access-Control-Allow-Credentials', true)
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization')
            ->header('Accept', 'application/json');

    }
}
