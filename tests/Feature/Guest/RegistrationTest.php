<?php

test('new users can register', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertNoContent();

    $response->assertStatus(200); // ->assertJson([ 'reponse' => "" ]);

    $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) && strlen($response['response']['access']['token']) > 0 ) );




});
