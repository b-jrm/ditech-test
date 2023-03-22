<?php

namespace App\Http\Api;

use Illuminate\Http\Request;

use App\Http\Api\Api;

class RickAndMorty extends Api{

    // Versions
    protected static $urlbase = [
        'v1' => "https://rickandmortyapi.com/api/",
    ];

    protected static $endpoint;

    /**
     * ---------------------------------------------------------------------------------------
     * Enpoints allowed Rick And Morty Api
     */

    protected static function endpoints(String $endpoint = ''): Void
    {
        $points = [
            'character' => [
                'parameters' => [
                    /**
                     * --------------------------------------------
                     * The id of the character.
                     */
                    "id" => [
                        "type" => "int",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The name of the character.
                     */
                    "name" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The status of the character ('Alive', 'Dead' or 'unknown').
                     */
                    "status" => [
                        "type" => "string",
                        "required" => false,
                        "allowed" => [ "alive", "dead", "unknown" ],
                    ],
                    /**
                     * --------------------------------------------
                     * The species of the character.
                     */
                    "species" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The type or subspecies of the character.
                     */
                    "type" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The gender of the character ('Female', 'Male', 'Genderless' or 'unknown').
                     */
                    "gender" => [
                        "type" => "string",
                        "required" => false,
                        "allowed" => [ "female", "male", "genderless", "unknown" ],
                    ],
                    /**
                     * --------------------------------------------
                     * Name and link to the character's origin location.
                     */
                    "origin" => [
                        "type" => "object",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Name and link to the character's last known location endpoint.
                     */
                    "location" => [
                        "type" => "object",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Link to the character's image. All images are 300x300px and most are medium shots or portraits since they are intended to be used as avatars.
                     */
                    "image" => [
                        "type" => "string", // Url
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * List of episodes in which this character appeared.
                     */
                    "episode" => [
                        "type" => "array", // Url's
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Link to the character's own URL endpoint.
                     */
                    "url" => [
                        "type" => "string", // Url
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Time at which the character was created in the database.
                     */
                    "created" => [
                        "type" => "string",
                        "required" => false,
                    ],
                ]
            ],
            'location' => [
                'parameters' => [
                    /**
                     * --------------------------------------------
                     * The id of the location.
                     */
                    "id" => [
                        "type" => "int",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The name of the location.
                     */
                    "name" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The type of the location.
                     */
                    "type" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The dimension in which the location is located.
                     */
                    "dimension" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * List of character who have been last seen in the location.
                     */
                    "residents" => [
                        "type" => "array", // Url's
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Link to the location's own endpoint.
                     */
                    "url" => [
                        "type" => "string", // Url
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Time at which the character was created in the database.
                     */
                    "created" => [
                        "type" => "string",
                        "required" => false,
                    ],
                ]
            ],
            'episode' => [
                'parameters' => [
                    /**
                     * --------------------------------------------
                     * The id of the episode.
                     */
                    "id" => [
                        "type" => "int",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The name of the episode.
                     */
                    "name" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * 	The air date of the episode..
                     */
                    "air_date" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * The code of the episode.
                     */
                    "episode" => [
                        "type" => "string",
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * List of characters who have been seen in the episode.
                     */
                    "characters" => [
                        "type" => "array", // Url's
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Link to the location's own endpoint.
                     */
                    "url" => [
                        "type" => "string", // Url
                        "required" => false,
                    ],
                    /**
                     * --------------------------------------------
                     * Time at which the character was created in the database.
                     */
                    "created" => [
                        "type" => "string",
                        "required" => false,
                    ],
                ]
            ]
        ];

        self::$endpoint = $points[$endpoint]?? [ 'error' => 'Not Found Endpoint ('.$endpoint.')' ];

    }

    public static function default()
    {
        try{
            
            parent::$url = self::$urlbase['v1'];

            return parent::get();

        }catch(Exception $e){
            return json_encode([ 'exception' => $e->getMessage() ]);
        }
    }

    public static function character(Request $data, $ids = '')
    {
        try{

            self::endpoints(__FUNCTION__);   
            
            if( isset(self::$endpoint['error']) ) return json_encode(self::$endpoint);
            
            parent::$url = self::$urlbase['v1'].__FUNCTION__."/{$ids}";
            
            self::assemble($data);

            return parent::get();

        }catch(Exception $e){
            return json_encode([ 'exception' => $e->getMessage() ]);
        }
    }

    public static function episode(Request $data, $ids = '')
    {
        try{

            self::endpoints(__FUNCTION__);   
            
            if( isset(self::$endpoint['error']) ) return json_encode(self::$endpoint);
            
            parent::$url = self::$urlbase['v1'].__FUNCTION__."/{$ids}";
            
            self::assemble($data);

            return parent::get();

        }catch(Exception $e){
            return json_encode([ 'exception' => $e->getMessage() ]);
        }
    }

    public static function location(Request $data, $ids = '')
    {
        try{

            self::endpoints(__FUNCTION__);   
            
            if( isset(self::$endpoint['error']) ) return json_encode(self::$endpoint);
            
            parent::$url = self::$urlbase['v1'].__FUNCTION__."/{$ids}";
            
            self::assemble($data);

            return parent::get();

        }catch(Exception $e){
            return json_encode([ 'exception' => $e->getMessage() ]);
        }
    }

    protected static function assemble($data = []){

        foreach(self::$endpoint as $key => $value){

            if( is_array($value) && count((array)$data) > 0 ){
                
                foreach( $value as $parameter => $constraints ){

                    // if( mb_strtolower(gettype($data[$parameter])) !== mb_strtolower($constraints['type']) )
                        // return [ 'error' => 'Parameter ['.$parameter.'] Type ('.mb_strtolower($constraints['type']).'), '.mb_strtolower(gettype($data[$parameter])).' Given.' ];

                    switch( mb_strtolower($constraints['type']) ){

                        case 'string':

                                if( $constraints['required'] && strlen($data[$parameter]) === 0 )
                                    return [ 'error' => 'Parameter ('.$parameter.') Is String Required, Value is => '.$data[$parameter] ];

                            break;

                        case 'int':

                                if( $constraints['required'] && !is_numeric($data[$parameter]) )
                                    return [ 'error' => 'Parameter ('.$parameter.') Is Integer Required, Value Is => '.$data[$parameter] ];

                            break;
                            
                        case 'array':

                                if( $constraints['required'] && !is_array($data[$parameter]) )
                                    return [ 'error' => 'Parameter ('.$parameter.') Is Array Required, Value Is => '.$data[$parameter] ];

                            break;

                    }

                    if( !is_null($data[$parameter]) )
                        parent::$parameters[$parameter] = $data[$parameter];
                    
                }
            }

        }

    }

}