<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

use App\Http\Traits\Msg;
use App\Http\Traits\Twitter;

class UsersController extends Controller
{
    use Msg, Twitter;

    public function list(){
        try{
            return  User::select(
                "id",
                "name as nombre",
                "avatar as imagen",
                "description as descripcion"
            )->get();
        }catch(Exception $e){
            return Msg::error(__FUNCTION__.": ".$e->getMessage());
        }
    }

    public function seeMe(Request $request)
    {
        try{
            if( !empty($request->user()) ){
                return Msg::warning("Usuario no encontrado",[ 'response' => Msg::success("Mi Información"), 'data' => $request->user() ]);
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

                return Msg::success("Usuario actualizado");
            }else{
                return Msg::warning("Usuario desconocido");
            }
        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function new(Request $request)
    {
        try{
            return response()->json($request->all());
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
                return Msg::warning("No ha sido creado, Inténtelo mas tarde");
            }
        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function byId(Int $id)
    {
        try{

            if($id){
                
                $user = User::find($id);
                
                if($user)
                    return Msg::success("Usuario encontrado",[ $user ]);
                else
                    return Msg::warning("No se ha encontrado");

            }else
                return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function modifyInfo(Request $request, Int $id)
    {
        try{
            return [ 'function' => __FUNCTION__, 'id' => $id, 'request' => $request->all() ];
            // if($id){
                
            //     $user = User::find($id);
                
            //     if($user)
            //         return Msg::success("Usuario encontrado",[ $user ]);
            //     else
            //         return Msg::warning("No se ha encontrado");

            // }else
            //     return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function modifyAvatar(Request $request, Int $id)
    {
        try{
            return [ 'function' => __FUNCTION__, 'id' => $id, 'request' => $request->file() ];
            // if($id){
                
                // $user = User::find($id);
                
                // if($user){
                    // $nameimage = "default.png";
                    // if($request->hasFile('avatar')){
                    //     $image = $request->file('avatar');
                    //     $extension = $image->extension();
                    //     $nameimage = "user_{$user->id}_avatar.{$extension}";
                    //     Storage::disk('public')->putFileAs('avatars/',$image,$nameimage);
                    // }
                //     return Msg::success("Usuario encontrado",[ $user ]);
                // }else
                //     return Msg::warning("No se ha encontrado");

            // }else
            //     return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }

    public function tweets(String $name)
    {
        try{
            // return [ 'function' => __FUNCTION__, 'name' => $name ];

            return Twitter::execute('statuses/user_timeline.json', [ 'screen_name' => $name ]);
            // if($id){
                
            //     $user = User::find($id);
                
            //     if($user)
            //         return Msg::success("Usuario encontrado",[ $user ]);
            //     else
            //         return Msg::warning("No se ha encontrado");

            // }else
            //     return Msg::warning("ID Invalido");

        }catch(Exception $e){
            return Msg::error(__FUNCTION__." exception: ".$e->getMessage());
        }
    }    

}
