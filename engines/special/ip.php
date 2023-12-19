<?php
    class IPRequest extends EngineRequest {
        public function parse_results($response) {
            return array(
                "special_response" => array(
                    "response" => $_SERVER["REMOTE_ADDR"],
                    "source" => null
                )
            );
        }
    }
?>
