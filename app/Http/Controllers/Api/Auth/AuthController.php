<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResponseResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', only: ['profile', 'logout']),
        ];
    }

    /**
     * Authenticate user with username or email, and password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\LoginResponseResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function login(Request $request): LoginResponseResource
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $credentials['username'])
            ->orWhere('email', $credentials['username'])
            ->first();

        throw_if(
            !$user ||
            !Auth::attempt(['username' => $user?->username, 'password' => $credentials['password']]) &&
            !Auth::attempt(['email' => $user?->email, 'password' => $credentials['password']]),
            ValidationException::withMessages([
                'username' => ['The provided credentials do not match our records.']
            ])
        );

        $token = $user->createToken('web-token');

        return new LoginResponseResource([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * Get the profile of an authenticated user.
     *
     * @return \App\Http\Resources\UserResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function profile(): UserResource
    {
        return new UserResource(request()->user());
    }

    /**
     * Log the user out and revoke the token that was used to authenticate the current request.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
