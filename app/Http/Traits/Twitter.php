<?php

namespace App\Http\Traits;

trait Twitter{

    // Versions
    protected static $base;

	protected static $versions = [
        'v1' => "https://api.twitter.com/1.1/",
        'v2' => "https://api.twitter.com/2/",
    ];
	
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

    public static function execute(String $endpoint, Array $params = [], Array $replaces = [], String $version = 'v2'){

        self::$base = self::$versions[$version];

        self::$endpoint = $endpoint;
        self::$params = $params;

        $build = self::build($replaces);

        if( !isset($build['fail']) )
            return self::exec();
        else
            return $build;

    }

    protected static function build(Array $replaces = []){

        self::$request = self::endpoints();

        if( !isset(self::$request['fail']) ){

            foreach(self::$request as $key => $value){
                
                if( $key === 'http' )
                    self::$http = $value;
                
                if( $key === 'replace' && $value )
                    self::$endpoint = self::replaces(self::$endpoint, $replaces);
                
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

        return self::$request;

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
                    curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode(self::$data)); 
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

                    'screen_name' => 'required',

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
            ],
            'lists/:id/tweets' => [
                'http' => 'GET',
                'replace' => true, // Determina si se debe o no remplazar alguna parte del enpoint, en este caso :id
                'parameters' => [

                    /**
                     * ---------------------------------------------------------------------
                     * Required. The ID of the List you wish to recieve Tweet details on.
                     */

                    'id' => 'required',

                    /**
                     * ---------------------------------------------------------------------
                     * Comma-separated list of fields for the Tweet object.
                     * attachments,author_id,context_annotations,conversation_id,created_at,
                     * entities,geo,id,in_reply_to_user_id,lang,non_public_metrics,
                     * organic_metrics,possibly_sensitive,promoted_metrics,public_metrics,
                     * referenced_tweets,reply_settings,source,text,withheld,edit_history_tweet_ids,
                     * edit_controls
                     */

                     'tweet.fields' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * Expansions enable requests to expand an ID into a full object in the 
                     * includes response object.
                     * Allowed value:
                     * author_id
                     * Default value: none
                     */

                    'expansions' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * Comma-separated list of fields for the User object. 
                     * Expansion required.
                     */

                    'user.fields' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * The maximum number of results to be returned by a request.
                     * Allowed values: 1 through 100.
                     * Default value: 100
                     */

                    'max_results' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * This parameter is used to get the next 'page' of results. 
                     * The value used with the parameter is pulled directly from the 
                     * response provided by the API, and should not be modified.
                     */

                     'pagination_token' => 'optional',


                ]
            ],
            'users/:id/tweets' => [
                'http' => 'GET',
                'replace' => true, // Determina si se debe o no remplazar alguna parte del enpoint, en este caso :id
                'parameters' => [

                    /**
                     * ---------------------------------------------------------------------
                     * Specifies the number of Tweets to try and retrieve, up to a 
                     * maximum of 100 per distinct request. 
                     */

                    'max_results' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * YYYY-MM-DDTHH:mm:ssZ (ISO 8601/RFC 3339). The oldest or earliest UTC
                     * timestamp from which the Tweets will be provided. Only the 3200 most 
                     * recent Tweets are available. Timestamp is in second granularity and 
                     * is inclusive (i.e. 12:00:01 includes the first second of the minute). 
                     * Minimum allowable time is 2010-11-06T00:00:00Z
                     */

                     'start_time' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * YYYY-MM-DDTHH:mm:ssZ (ISO 8601/RFC 3339). The oldest or earliest UTC 
                     * timestamp from which the Tweets will be provided. Only the 3200 most 
                     * recent Tweets are available. Timestamp is in second granularity and 
                     * is inclusive (i.e. 12:00:01 includes the first second of the minute). 
                     * Minimum allowable time is 2010-11-06T00:00:00Z
                     */

                    'end_time' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * Returns results with an ID greater than (that is, more recent than) 
                     * the specified ID. Only the 3200 most recent Tweets are available. 
                     * The result will exclude the `since_id`. If the limit of Tweets has 
                     * occurred since the `since_id`, the `since_id` will be forced to the 
                     * oldest ID available.
                     */

                    'since_id' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * Returns results with an ID less less than (that is, older than) the 
                     * specified ID. Only the 3200 most recent Tweets are available. The 
                     * result will exclude the `until_id`. If the limit of Tweets has 
                     * occurred since the `until_id`, the `until_id` will be forced to 
                     * the most recent ID available.
                     */

                    'until_id' => 'optional',

                    /**
                     * ---------------------------------------------------------------------
                     * This parameter is used to move forwards or backwards through pages 
                     * of results, based on the value of the `next_token` or `previous_token`
                     * in the response. The value used with the parameter is pulled directly 
                     * from the response provided by the API, and should not be modified.
                     */

                     'pagination_token' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * Comma-separated list of fields to expand. Expansions enable requests
                     *  to expand an ID into a full object in the includes response object.
                     * Allowed values: 
                     * attachments.poll_ids,
                     * attachments.media_keys,
                     * author_id,
                     * geo.place_id,
                     * in_reply_to_user_id,
                     * referenced_tweets.id,
                     * entities.mentions.username,
                     * referenced_tweets.id.author_id,
                     * edit_history_tweet_ids
                     */

                     'expansions' => 'optional',

                     /**
                     * ---------------------------------------------------------------------
                     * Comma-separated list of fields for the Tweet object.
                     * Allowed values:
                     * attachments,
                     * author_id,
                     * context_annotations,
                     * conversation_id,
                     * created_at,
                     * entities,
                     * geo,
                     * id,
                     * in_reply_to_user_id,
                     * lang,
                     * non_public_metrics,
                     * organic_metrics,
                     * possibly_sensitive,
                     * promoted_metrics,
                     * public_metrics,
                     * referenced_tweets,
                     * reply_settings,
                     * source,
                     * text,
                     * withheld,
                     * edit_history_tweet_ids,
                     * edit_controls
                     *     
                     * Default values: id, text, edit_history_tweet_ids
                     */

                     'tweet.fields' => 'optional',


                ]
            ],
        ];
        
        return $points[self::$endpoint]?? [ 'fail' => 'Not Found Endpoint Twitter ('.self::$endpoint.')' ];

    }

    protected static function replaces($endpoint, $replaces){
        
        if( count($replaces) ){

            foreach($replaces as $key => $value){

                if( is_numeric(strpos($endpoint,$key)) )
                    self::$endpoint = str_replace($key,$value,self::$endpoint);

            }

        }
        
        return $endpoint;

    }

}
