<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Shuttle\PhpPack\Classes\Exceptions\ApiUnauthorizedException;
use Shuttle\PhpPack\Traits\ShuttlePhpPackTrait;

class TokenMiddleware
{

    use ShuttlePhpPackTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization') ;
        try {
            if ($authorizationHeader && $this->tokenVerify($authorizationHeader)) {
                $decodedToken = $this->tokenVerify($authorizationHeader);
                $request->setUserResolver(fn() => $decodedToken['user']);
                return $next($request);
            }else{
                return response()->json(['message' => 'unauthorized', 'status_code' => 401], 401);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'unauthorized', 'status_code' => 401], 401);
        }
    }
}
