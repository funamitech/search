<?php
    class UserAgentRequest extends EngineRequest {
        function __construct($query, $page, $mh, $config) {
            $this->query = $query;
            $this->page = $page;
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
