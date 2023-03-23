<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Http\Traits\Msg;

class AuthenticateController extends Controller
{

    use Msg;

    public function register(Request $request)
    {

        $ready = User::where('email',$request->email)->orWhere('name',$request->name)->exists();

        if( $ready ){
            return Msg::warning("Ya existe un usuario ({$request->email}) o ({$request->name})");
        }else{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if( $user ){
                $token = $user->createToken('authenticated')->plainTextToken;
                return Msg::success("Registrado con éxito",["user" => $user, "access" => [ "type" => 'Bearer', "token" => $token ] ]);
            }else{
                return Msg::warning("No se ha registrado el usuario");
            }
        }

    }

    public function login(Request $request)
    {
        if ( !Auth::attempt($request->only('email', 'password')) )
            return Msg::warning("Credenciales incorrectas", [], 401);

        $user = User::where('email',$request->email)->firstOrFail();

        $token = $user->createToken('authenticated')->plainTextToken;

        return response()->json( ["response" => "Welcome!, {$user->name}", "access" => [ "type" => 'Bearer', "token" => $token ] ] );
    }

    public function logout()
    {
        if( !empty(auth()) ){
            auth()->user()->tokens()->delete();
            return Msg::success(auth()->user()->name."!, Session Finally");
        }else
            return Msg::warning("Session no encontrada!");
    }

}
