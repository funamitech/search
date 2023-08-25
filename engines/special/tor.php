<?php

    class TorRequest extends EngineRequest {
        public function get_request_url() {
            return "https://check.torproject.org/torbulkexitlist";
        }

        public function get_results() {
            $response = curl_multi_getcontent($ch);

            $formatted_response = strpos($response, $_SERVER["REMOTE_ADDR"]) ? "It seems like you are using Tor" : "It seems like you are not using Tor";
            $source = "https://check.torproject.org";
            
            return array(
                "special_response" => array(
                    "response" => $formatted_response,
                    "source" => $source
                )
            );
        }
    }
?>
