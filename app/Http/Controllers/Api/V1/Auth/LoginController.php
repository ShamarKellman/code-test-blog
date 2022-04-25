<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * @param  LoginRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = User::query()
                    ->where('email', $request->email)
                    ->firstOrFail();

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }
}
