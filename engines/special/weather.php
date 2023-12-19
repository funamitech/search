<?php
    class WeatherRequest extends EngineRequest {
        public function get_request_url () {
            return "https://wttr.in/@" . $_SERVER["REMOTE_ADDR"] . "?format=j1";
        }
        
        public function parse_results($response) {
            $json_response = json_decode($response, true);

            if (!$json_response)
                return array();

            $current_weather = $json_response["current_condition"][0];

            $temp_c = $current_weather["temp_C"];
            $temp_f = $current_weather["temp_F"];
            $description = $current_weather["weatherDesc"][0]["value"];

            $formatted_response = "$description - $temp_c °C | $temp_f °F";

            $source = "https://wttr.in";
            return array(
                "special_response" => array(
                    "response" => htmlspecialchars($formatted_response),
                    "source" => $source
                )
            );
        }
    }
?>
