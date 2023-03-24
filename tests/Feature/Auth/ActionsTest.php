<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

use App\Models\User;
use App\Models\Token;

class ActionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_usuario_autenticado_puede_ver_su_información(): void
    {
        $user = User::inRandomOrder()->first();
        
        $user->tokens()->delete();

        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/me');
        
            // $response->dd();
            $response->assertStatus(200);

            $this->assertTrue( (isset($response['type']) && is_numeric(strpos($response['type'],'success'))) );

        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_actualizar_su_información(): void
    {
        $user = User::inRandomOrder()->first();
        
        $user->tokens()->delete();

        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/me', [
                'email' => $user->email,
                'description' => $user->description.' updated',
                'name' => $user->name.' updated',
            ]);
        
            $response->assertStatus(200);
    
            $this->assertTrue( (isset($response['type']) && is_numeric(strpos($response['type'],'success'))) );

        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_todos_ver_los_usuarios_de_la_base_de_datos(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/users');
    
            $response->assertStatus(200);

            $this->assertTrue( isset($response[0]['id']) );
        
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_buscar_un_usuario_por_su_id(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/users/'.rand(1,20));

            if( is_numeric(strpos($response['message'],'could not be found')) ){
                $response->assertStatus(404);
            }else{
                $response->assertStatus(200);
                $this->assertTrue(  is_numeric(strpos($response['type'],'success')) );
            }
        
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_crear_usuarios(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $name = fake('es_ES')->name();

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/user/create',[
                'email' => str_replace(" ","",mb_strtolower($name))."@faker.co",
                'description' => 'User Faker From Testing',
                'name' => $name,
            ]);
            
            $response->assertStatus(200);
            $this->assertTrue(  is_numeric(strpos($response['type'],'success')) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_actualizar_informacion_de_cualquier_usuario(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $name = fake('es_ES')->name();

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/user/'.rand(1,20).'/update',[
                'email' => str_replace(" ","",mb_strtolower($name))."@faker.co",
                'description' => 'User Faker From Testing',
                'name' => $name,
            ]);

            $response->assertStatus(200);

            $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) || is_numeric(strpos($response['type'],'warning')) ) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_actualizar_avatar_de_cualquier_usuario(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            Storage::fake('public');
            $avatar = UploadedFile::fake()->create('avatar.jpg');

            // dd($avatar);

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->postJson('/api/user/'.rand(1,20).'/update',[
                'avatar' => $avatar,
                'description' => "now uploaded avatar",
            ])->assertStatus(200);

            Storage::disk('public')->assertExists($avatar->hashName());

            $this->assertTrue( ( is_numeric(strpos($response['type'],'success')) || is_numeric(strpos($response['type'],'warning')) ) );
            
        }else $this->assertTrue( false );
    }

}
