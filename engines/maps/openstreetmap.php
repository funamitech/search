<?php
    require "engines/text/text.php";
    class OSMRequest extends EngineRequest {
        public function get_request_url() {

            $query_encoded = str_replace("%22", "\"", urlencode($this->query));
            $results = array();

            // TODO allow the nominatim instance to be customised
            $url = "https://nominatim.openstreetmap.org/search?q=$query_encoded&format=json";

            return $url;
        }


        public function parse_results($response) {
            $json_response = json_decode($response, true);
            if (!$json_response)
                return array();

            $results = array();
            foreach ($json_response as $item) {
                array_push($results, array(
                    "title" => $item["name"],
                    "description" => $item["display_name"],
                    "url" => "https://www.openstreetmap.org/node/" . $item["osm_id"],
                    "base_url" => "www.openstreetmap.org"
                ));
            }
            return $results;
        }

        public static function print_results($results, $opts) {
            TextSearch::print_results($results, $opts);
        }
    }
?>
