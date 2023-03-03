<?php

namespace App\Http\Traits;

// use Illuminate\Http\Response;

trait Msg{

	private static $type;
	private static $message;

    public static function success(String $text = '', Array $data = [], $httpResponse = 200)
    {
        try{
            self::$type = __FUNCTION__;
            self::$message = ( (strlen($text)) ? $text : 'Correcto!, Se ha procesado la petición' );
            return self::show($data, $httpResponse);
        }catch(Exception $e){
            return self::$error($e->getMessage());
        }
    }

    public static function warning(String $text = '', Array $data = [], $httpResponse = 200)
    {
        try{
            self::$type = __FUNCTION__;
            self::$message = ( (strlen($text)) ? $text : 'Importante!, Ha ocurrido algo inesperado' );
            return self::show($data, $httpResponse);
        }catch(Exception $e){
            return self::$error($e->getMessage());
        }
    }

    public static function error(String $exceptionText, Array $data = [], $httpResponse = 500)
    {
        self::$type = __FUNCTION__;
        self::$message = "Exception!, {$exceptionText}";
        return self::show($data, $httpResponse);
    }

    protected static function show(Array $data, $httpResponse = 200)
    {
        return response()->json([
            'type' => self::$type,
            'message' => self::$message,
            'response' => $data
        ], $httpResponse);
    }
}
