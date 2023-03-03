<?php

namespace App\Http\Traits;

trait Twitter{

    // Versions
	// protected $base = "https://api.twitter.com/2/";
	protected $base = "https://api.twitter.com/1.1/"; 
	
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

    protected function parameters(String $point): Array
    {
        $points = [
            'statuses/user_timeline.json' => [
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
        
        return $points[$point];

    }

}
