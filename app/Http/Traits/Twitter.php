<?php

namespace App\Http\Traits;

trait Twitter{

	protected $base = "https://api.twitter.com/2/";
	protected $authorize = ""; // Token Access Twitter
	protected $header = [];
	protected $params = [];
	protected $response;
	protected $error;
	protected $curl;
	protected $endpoint;

    protected function set(String $endpoint){
        try{
            $this->endpoint = $this->base.$endpoint;
            return $this;
        }catch(Exception $e){
            return self::$error($e->getMessage());
        }
    }

    protected function exec(){
        try{
            $this->curl = curl_init($this->endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));           
            $response = curl_exec($ch);
            $this->response = json_decode($response);
            $this->error = curl_error($ch);
        }catch(Exception $e){
            return self::$error($e->getMessage());
        }
    }

}
