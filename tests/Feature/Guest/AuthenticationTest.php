<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

use App\Models\User;

class AuthenticationTest extends TestCase
{

    public function test_usuario_puede_hacer_login_y_obtener_su_token(): void
    {
        $user = USer::factory()->create([
            'password' => bcrypt($password = 'pass_testing'),
        ]);
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
    
        $response->assertStatus(200);
    
        $this->assertTrue( ( is_numeric(strpos($response['response'],'Welcome')) && strlen($response['access']['token']) > 0 ) );
    }

    public function test_usuario_autenticado_no_puede_autenticar_con_credenciales_incorrectas(): void
    {
        $user = User::inRandomOrder()->first();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertTrue( ($response['message'] === 'Credenciales incorrectas') );

        $this->assertGuest();
    }

}
