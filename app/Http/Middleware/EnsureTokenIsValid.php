<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header('Authorization')) return response()->json(['message' => 'Authentication Failed'], 401);

        list(, $token) = explode(" ",$request->header('Authorization'));
        list($userId, $password) = explode(':', base64_decode($token));
        if (!$this->loginUser($userId, $password)) {
            return response()->json(['message' => 'Authentication Failed'], 401);
        }
        return $next($request);
    }

    private function loginUser(string $userId, string $password)
    {
        $user = User::where('user_id', $userId)
            ->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return true;
        }

        return false;
    }
}
