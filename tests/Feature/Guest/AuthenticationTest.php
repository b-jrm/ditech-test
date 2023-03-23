<?php

use App\Models\User;

test('Usuario puede hacer login y obtener su token', function () {
    $user = User::factory()->create();
    
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);

    $this->assertTrue( ( is_numeric(strpos($response['response'],'Welcome')) && strlen($response['access']['token']) > 0 ) );
});

test('Usuario no puede autenticar con credenciales incorrectas', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertTrue( ($response['message'] === 'Credenciales incorrectas') );

    $this->assertGuest();
});
