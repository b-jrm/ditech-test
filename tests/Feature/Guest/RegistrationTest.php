<?php

test('Usuario puede registrarse y obtener su token', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200);
    
    $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) && strlen($response['response']['access']['token']) > 0 ) );

});

test('Usuario no puede registrarse con informacion incompleta', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->postJson('/api/register', [
        'name' => '',
        'email' => null,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422);
    
    $this->assertTrue( ( is_numeric(strpos($response['message'],'is required')) && count($response['errors']) > 0 ) );
});
