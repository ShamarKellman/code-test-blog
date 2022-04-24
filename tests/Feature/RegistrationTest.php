<?php

declare(strict_types=1);

test('new users can register')
    ->post('/api/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
    ->assertOk()
    ->assertJsonStructure([
        'token',
    ]);
