<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Models\User;

class RegistrationTest extends TestCase
{

    public function test_usuario_puede_registrarse_y_obtener_su_token(): void
    {
        $name = fake('es_ES')->name();
        
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/register', [
            'name' => $name,
            'email' => str_replace(" ","",mb_strtolower($name))."@faker.co",
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    
        $response->assertStatus(200);
        
        $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) && strlen($response['response']['access']['token']) > 0 ) );
    }

    public function test_usuario_autenticado_no_puede_registrarse_con_informacion_incompleta(): void
    {
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
    }

}