<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class RickAndMortyTest extends TestCase
{
    public function test_usuario_autenticado_puede_acceder_a_la_api(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty')->assertStatus(200);

            $this->assertTrue( ( isset($response['characters']) && isset($response['locations']) && isset($response['episodes']) ) );
            
        }else $this->assertTrue( false );
    }

    /**
     * ---------------------
     * Characters API  -----
     * ---------------------
     */
    public function test_usuario_autenticado_puede_ver_todos_los_personajes(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/character')->assertStatus(200);

            $this->assertTrue( isset($response['results']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_un_personaje_por_id(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/character/'.rand(1,100))->assertStatus(200);

            $this->assertTrue( isset($response['id']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_varios_personajes_por_ids(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $ids = array();
            for ($i=0; $i < 4; $i++) {
                $id = rand(1,100);
                if( !array_search($id, $ids) ) array_push($ids,$id);
            }
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/character/'.implode(',',$ids))->assertStatus(200);

            $this->assertTrue( isset($response[0]['id']) );
            
        }else $this->assertTrue( false );
    }

    /**
     * ---------------------
     * Locations API  -----
     * ---------------------
     */
    public function test_usuario_autenticado_puede_ver_todos_los_lugares(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/location')->assertStatus(200);

            $this->assertTrue( isset($response['results']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_un_lugar_por_id(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/location/'.rand(1,40))->assertStatus(200);

            $this->assertTrue( isset($response['id']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_varios_lugares_por_ids(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $ids = array();
            for ($i=0; $i < 4; $i++) {
                $id = rand(1,100);
                if( !array_search($id, $ids) ) array_push($ids,$id);
            }
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/location/'.implode(',',$ids))->assertStatus(200);

            $this->assertTrue( isset($response[0]['id']) );
            
        }else $this->assertTrue( false );
    }

    /**
     * ---------------------
     * Episodes API  -----
     * ---------------------
     */
    public function test_usuario_autenticado_puede_ver_todos_los_episodios(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/episode')->assertStatus(200);

            $this->assertTrue( isset($response['results']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_un_episodio_por_id(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/episode/'.rand(1,40))->assertStatus(200);

            $this->assertTrue( isset($response['id']) );
            
        }else $this->assertTrue( false );
    }

    public function test_usuario_autenticado_puede_ver_varios_episodios_por_ids(): void
    {
        $user = User::inRandomOrder()->first();

        $user->tokens()->delete();
        
        $token = $user->createToken('authenticated')->plainTextToken;

        if( !is_null($token) && strlen($token) > 0 ){

            $ids = array();
            for ($i=0; $i < 4; $i++) {
                $id = rand(1,100);
                if( !array_search($id, $ids) ) array_push($ids,$id);
            }
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ])->getJson('/api/rickandmorty/episode/'.implode(',',$ids))->assertStatus(200);

            $this->assertTrue( isset($response[0]['id']) );
            
        }else $this->assertTrue( false );
    }
}
