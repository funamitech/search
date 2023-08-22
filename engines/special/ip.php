<?php
    class IPRequest extends EngineRequest {
        function get_results() {
            return array(
                "special_response" => array(
                    "response" => $_SERVER["REMOTE_ADDR"],
                    "source" => null
                )
            );
        }
    }
?>
