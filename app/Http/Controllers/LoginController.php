<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
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
