<?php

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * -------------------------------------------------------------------------------------------------
 * Tests User Auth With Access Token
 */

test('Usuario autenticado puede actualizar su información', function () {
    
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
        ])->postJson('/api/me', [
            'email' => substr($user->email, 0, -1),
            'description' => $user->description.' updated',
            'name' => $user->name.' updated',
        ]);
    
        $response->assertStatus(200);

        $this->assertTrue( is_numeric(strpos($response['type'],'success')) );

    }else $this->assertTrue( false );

});

test('Autenticado, puede todos ver los usuarios de la base de datos', function () {
    
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
        ])->getJson('/api/users');
    
        $response->assertStatus(200);

        $this->assertTrue( isset($response[0]['id']) );

    }else $this->assertTrue( false );

});

test('Autenticado, se puede buscar un usuarios por su ID', function () {
    
    $token = Token::first();
    
    dd($token);

    if( strlen($token) > 0 ){

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/users/'.rand(1,20));
    
        if( is_numeric(strpos($response['message'],'could not be found')) )
            $response->assertStatus(404);
        else
            $response->assertStatus(200);

        $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) || is_numeric(strpos($response['type'],'warning')) ) );

    }else $this->assertTrue( false );

    $token = Token::first();

    dd($token);

});
