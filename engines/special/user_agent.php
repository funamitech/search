<?php
    class UserAgentRequest extends EngineRequest {
        function get_results() {
            return array(
                "special_response" => array(
                    "response" => $_SERVER["HTTP_USER_AGENT"], 
                    "source" => null
                )
            );                   
        }
    }
?>
