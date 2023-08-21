<?php
    class IPRequest extends EngineRequest {
        function __construct($query, $page, $mh, $config) {
            $this->query = $query;
        }

        function get_results()
        {
                return array(
                    "special_response" => array(
                        "response" => $_SERVER["REMOTE_ADDR"],
                        "source" => null
                    )
                );
        }
    }
?>
