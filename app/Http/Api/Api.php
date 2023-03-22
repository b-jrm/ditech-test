<?php

namespace App\Http\Api;

class Api{

	protected static $url;
	protected static $headers = [];
	protected static $parameters = [];
	protected static $response;
	protected static $request;
	protected static $error;
	protected static $curl;

	public static function get($data = [])
	{
		return self::run(mb_strtoupper(__FUNCTION__),$data);
	}

	public static function post($data = [])
	{
		return self::run(mb_strtoupper(__FUNCTION__),$data);
	}

	public static function put($data = [])
	{
		return self::run(mb_strtoupper(__FUNCTION__),$data);
	}

	public static function delete($data = [])
	{
		return self::run(mb_strtoupper(__FUNCTION__),$data);
	}

	public static function any($data = [])
	{
		return self::run(mb_strtoupper(__FUNCTION__),$data);
	}

	protected static function run(String $method, Array $parameters = [])
	{
		try{

			self::$curl = curl_init();

			curl_setopt(self::$curl, CURLOPT_URL,self::$url);
			curl_setopt(self::$curl, CURLOPT_HTTPHEADER, self::$headers);
			curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);

			if( count($parameters) ){
				foreach( $parameters as $key => $value ){
					self::$parameters[$key] = $value;
				}
			}
			if( count(self::$parameters) ){
				switch($method){
					case 'GET':
						curl_setopt(self::$curl, CURLOPT_URL,self::$url."?".http_build_query(self::$parameters));
					break;
					case 'POST':
						curl_setopt(self::$curl, CURLOPT_POST, true);
						curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode(self::$parameters));
					break;
				}
			}
			
			self::$response = curl_exec(self::$curl);

			if( curl_error(self::$curl) ){
				self::$error = [
					'error' => curl_getinfo(self::$curl, CURLINFO_HEADER_OUT),
                    'message' => curl_errno(self::$curl)." - ".curl_error(self::$curl)
				];
			}
			
			curl_close(self::$curl);

			if( is_array(self::$error) ) return self::$error;

			return self::object();
			
		}catch(Exception $e){
			return [ 'exception' => $e->getMessage() ];
		}
	}

	private static function object(){
		return json_decode(self::$response); // Object of class stdClass
	}

	private static function json(){
		return json_decode(json_encode(self::$response)); // Object JSON
	}

}