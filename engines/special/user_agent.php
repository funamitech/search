<?php
    class UserAgentRequest extends EngineRequest {
        function __construct($query, $mh, $config) {
            $this->query = $query;
        }

        function get_results()
        {
            return array(
                "special_response" => array(
                    "response" => $_SERVER["HTTP_USER_AGENT"], 
                    "source" => null
                )
            );                   
        }
    }
?>
