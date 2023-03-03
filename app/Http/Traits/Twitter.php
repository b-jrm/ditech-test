<?php

namespace App\Http\Traits;

trait Twitter{

    // Versions
	// protected $base = "https://api.twitter.com/2/";
	protected static $base = "https://api.twitter.com/1.1/"; 
	
    protected static $typeauth = "Bearer"; // Type Authorized Access Twitter
    protected static $token = ""; // Key Token Access Twitter
	protected static $headers = [];
	protected static $params = [];
	protected static $data = [];
	protected static $http = 'GET';
	protected static $response;
	protected static $request;
	protected static $error;
	protected static $curl;
	protected static $endpoint;

    public static function execute(String $endpoint, Array $params = []){

        self::$endpoint = $endpoint;
        self::$params = $params;

        self::build();

        return self::exec();

    }

    protected static function build(){

        self::$request = self::endpoints();

        if( count(self::$request) ){

            foreach(self::$request as $key => $value){
                
                if( $key === 'http' )
                    self::$http = $value;
                
                if( is_array($value) ){
                    foreach( $value as $param => $required ){

                        if( ( !isset(self::$params[$param]) || empty(self::$params[$param]) ) && $required === 'required' )
                            return [ 'response' => 'Required Data ['.$param.'], Is Empty' ];
                        else if( isset(self::$params[$param]) && !empty(self::$params[$param]) )
                            self::$data[$param] = self::$params[$param];
                        
                    }
                }
            }

        }

    }

    protected static function exec(){
        try{
            self::$headers = array(
                'Accept: application/json',
                "Authorization: ".self::$typeauth." ".self::$token
            );
    
    
            self::$curl = curl_init();

            curl_setopt(self::$curl, CURLOPT_URL,self::$base.self::$endpoint);
            curl_setopt(self::$curl, CURLOPT_HTTPHEADER, self::$headers);
            curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);

            switch(self::$http){
                case 'POST':
                    curl_setopt(self::$curl, CURLOPT_POST, true);
                    curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode(self::$params)); 
                break;
            }
            
            self::$response = curl_exec(self::$curl);

            return self::$response;

        }catch(Exception $e){
            return self::$error($e->getMessage());
        }
    }

    protected static function endpoints(): Array
    {
        $points = [
            'statuses/user_timeline.json' => [
                'http' => 'GET',
                'parameters' => [

                    /**
                     * ---------------------------------------------------------------------
                     * The ID of the user for whom to return results.
                     */

                    'user_id' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * The screen name of the user for whom to return results.
                     */

                    'screen_name' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * Returns results with an ID greater than (that is, more recent than) 
                     * the specified ID. There are limits to the number of Tweets that can 
                     * be accessed through the API. If the limit of Tweets has occured 
                     * since the since_id, the since_id will be forced to the oldest ID 
                     * available.
                     */

                    'since_id' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * Specifies the number of Tweets to try and retrieve, up to a maximum 
                     * of 200 per distinct request. The value of count is best thought of 
                     * as a limit to the number of Tweets to return because suspended or 
                     * deleted content is removed after the count has been applied. We 
                     * include retweets in the count, even if include_rts is not supplied. 
                     * It is recommended you always send include_rts=1 when using this API
                     *  method.
                     */

                    'count' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * Returns results with an ID less than (that is, older than) or equal 
                     * to the specified ID
                     */

                    'max_id' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * When set to either true , t or 1 , each Tweet returned in a timeline 
                     * will include a user object including only the status authors numerical 
                     * ID. Omit this parameter to receive the complete user object
                     */

                    'trim_user' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * This parameter will prevent replies from appearing in the returned 
                     * timeline. Using exclude_replies with the count parameter will mean 
                     * you will receive up-to count tweets — this is because the count 
                     * parameter retrieves that many Tweets before filtering out retweets 
                     * and replies.
                     */

                    'exclude_replies' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * When set to false , the timeline will strip any native retweets 
                     * (though they will still count toward both the maximal length of the 
                     * timeline and the slice selected by the count parameter). Note: If 
                     * you're using the trim_user parameter in conjunction with include_rts, 
                     * the retweets will still contain a full user object
                     */

                     'include_rts' => 'optional',

                ]
            ]
        ];
        
        return $points[self::$endpoint]?? [ 'Not Found Endpoint ('.self::$endpoint.')' ];

    }

}
