<?php
    class UserAgentRequest extends EngineRequest {
        public function parse_results($response) {
            return array(
                "special_response" => array(
                    "response" => $_SERVER["HTTP_USER_AGENT"], 
                    "source" => null
                )
            );                   
        }
    }
?>
