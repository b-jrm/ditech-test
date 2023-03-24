<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

use App\Models\User;

class LogoutTest extends TestCase
{
    public function test_usuario_autenticado_puede_borrar_sus_tokens_de_acceso(): void
    {
        $user = User::inRandomOrder()->first();
        
        $user->tokens()->delete();

        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/logout');
        
            $response->assertStatus(200);

            $this->assertTrue( (isset($response['type']) && is_numeric(strpos($response['type'],'success'))) );

        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_no_puede_acceder_a_la_informacion_con_tokens_invalidos(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.Str::random(42),
        ])->postJson('/api/me');
    
        $response->assertStatus(401);
    
        $this->assertTrue( ( is_numeric(strpos($response['message'],'Unauthenticated')) ) );
    }

}