<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Http\Traits\Msg;
use App\Http\Traits\Twitter;

class UsersController extends Controller
{
    use Msg, Twitter;

    protected $baseImage;

    public function __construct(){
        $this->baseImage = env("APP_URL")."/storage/avatars";
    }

    public function list(){
        try{
            return  User::select(
                "id",
                "name as nombre",
                // DB::raw("CONCAT('{$this->baseImage}/',avatar) AS imagen"),
                DB::raw("IF( SUBSTR(avatar,0,3) = 'http' , avatar, CONCAT('{$this->baseImage}/',avatar) ) AS imagen"),
                "description as descripcion"
            )->get();
        }catch(Exception $e){
            return Msg::error(__FUNCTION__.": ".$e->getMessage());
        }
    }

    public function byId(Int $id)
    {
        try{

            if($id){
                
                $user = User::find($id);
                
                if($user){
                    // $user->avatar = "{$this->baseImage}/{$user->avatar}";
                    $user->avatar = ( (is_numeric(strpos($user->avatar,'http'))) ? $user->avatar : "{$this->baseImage}/{$user->avatar}");
                    return Msg::success("Usuario encontrado",[ $user ]);
                }else
                    return Msg::warning("No se ha encontrado ningun usuario con el ID {$id}");

            }else
                return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function seeMe(Request $request)
    {
        try{
            // return $request->user()->id;
            if( !empty($request->user()) ){
                // $request->user()->avatar = "{$this->baseImage}/{$request->user()->avatar}";
                $request->user()->avatar = ( (is_numeric(strpos($request->user()->avatar,'http'))) ? $request->user()->avatar : "{$this->baseImage}/{$request->user()->avatar}");
                return Msg::success("Mi información", [ 'user' => $request->user() ] );
            }else{
                return Msg::warning("Usuario no encontrado");
            }
        }catch(Exception $e){
            return Msg::error(__FUNCTION__.": ".$e->getMessage());
        }
    }

    public function storeMe(Request $request)
    {
        try{
            $user = User::find($request->user()->id);

            if( !empty($user) ){
                $user->name = $request->name?? $user->name;
                $user->email = $request->email?? $user->email;
                $user->description = $request->description?? $user->description;
                
                // Avatar (Image)
                // $user->avatar = $request->avatar?? $user->avatar;

                // Password
                // $user->password = $request->password?? $user->password;

                $user->save();

                return Msg::success("Usuario actualizado correctamente");
            }else
                return Msg::warning("Usuario desconocido");
            
        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function new(Request $request)
    {
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'description' => ['required', 'string'],
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->name), // Default Password Is Name
                'description' => $request->description,
            ]);

            if( !empty($user) ){
                return Msg::success("Usuario creado");
            }else{
                return Msg::warning("No ha podido crear el usuario");
            }
        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function modifyInfo(Request $request, Int $id)
    {
        try{

            if($id){

                if( ! User::where('email',$request->email)->where('id', '!=', $id )->exists() ){

                    $user = User::find($id);
                    
                    if($user){
                        $user->name = $request->name?? $user->name;
                        $user->email = $request->email?? $user->email;
                        $user->description = $request->description?? $user->description;
        
                        $user->save();
        
                        return Msg::success("Usuario ({$user->name}) actualizado correctamente");
                    }else
                        return Msg::warning("No se ha encontrado");

                }else
                    return Msg::warning("Ya existe un usuario con el email indicado");

            }else
                return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function modifyAvatar(Request $request, Int $id)
    {
        try{
            if($id){
                
                $user = User::find($id);
                
                if($user){

                    $nameimage = "default.png";
                    if($request->hasFile('avatar')){
                        $image = $request->file('avatar');
                        $extension = $image->extension();
                        $nameimage = "user_{$user->id}_avatar.{$extension}";
                        Storage::disk('public')->putFileAs('avatars/',$image,$nameimage);
                    }
                    $user->avatar = $nameimage;
                    $user->save();
                    
                    $user->avatar = "{$this->baseImage}/{$nameimage}";
                    return Msg::success("Imagen de usuario actualizada",[ $user ]);
                }else
                    return Msg::warning("No se ha encontrado el usuario");

            }else
                return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    } 

    public function tweetsByIdUser($user_id)
    {
        try{

            if( $user_id > 0 && !is_numeric(strpos($user_id,'{'))  )
                return Twitter::execute('users/:id/tweets', [ 'max_results' => 10 ], [ ':id' => $user_id ]);
            else
                return Msg::warning("ID Invalido, Debe ser numerico");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    // Testing Twitter
    public function tweetsByName($name)
    {
        try{

            if( strlen($name) > 0 && !is_numeric(strpos($name,'{'))  )
                return Twitter::execute('statuses/user_timeline.json', [ 'screen_name' => $name, 'max_results' => 10 ]);
            else
                return Msg::warning("Nombre Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }    

    public function tweetsById($id)
    {
        try{
            
            if( $id > 0 && !is_numeric(strpos($id,'{'))  )
                return Twitter::execute('lists/:id/tweets', [ 'max_results' => 10 ], [ ':id' => $id ]);
            else
                return Msg::warning("ID Invalido, Debe ser numerico");
           

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }   

}
