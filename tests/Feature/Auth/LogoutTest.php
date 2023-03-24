<?php

use App\Models\User;
use Illuminate\Support\Str;

test('Usuario puede borrar sus tokens de acceso', function () {
    
    $user = User::factory()->create();
    
    $response = $this->withHeaders([
        'Accept' => 'application/json',
    ])->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    if( is_numeric(strpos($response['response'],'Welcome')) && strlen($response['access']['token']) > 0 ){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $response['access']['type'].' '.$response['access']['token'],
        ])->postJson('/api/logout');
    
        $response->assertStatus(200);

        $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) && is_numeric(strpos($response['message'],'Session Finally')) ) );

    }else $this->assertTrue( false );

});

test('Usuario no puede acceder a la informacion con tokens invalidos', function () {
    
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.Str::random(42),
    ])->postJson('/api/me');

    $this->assertTrue( ( is_numeric(strpos($response['message'],'Unauthenticated')) ) );

});