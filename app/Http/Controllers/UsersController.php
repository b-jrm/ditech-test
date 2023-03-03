<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

use App\Http\Traits\Msg;

class UsersController extends Controller
{
    use Msg;

    public function list(){
        // DB::raw("CONCAT(".asset("").",' - ',cities.name) AS imagen"),
        return response()->json($reqreturnuest->all());
        try{
            return  User::select(
                "avatar as imagen",
                "name as nombre",
                "description as descripcion"
            )->get();
        }catch(Exception $e){
            return Msg::error(__METHOD__.": ".$e->getMessage());
        }
    }

    public function seeMe(Request $request)
    {
        try{
            if( !empty($request->user()) ){
                return [ 'response' => Msg::success("Mi Información"), 'data' => $request->user() ];
            }else{
                return Msg::warning("Usuario no encontrado");
            }
        }catch(Exception $e){
            return Msg::error(__METHOD__.": ".$e->getMessage());
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
            return Msg::error(__METHOD__.": ".$e->getMessage());
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
            return Msg::error(__METHOD__.": ".$e->getMessage());
        }
    }

    public function byId(Request $request){
        return $request->all();
    }

}
