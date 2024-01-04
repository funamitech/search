<?php
    class PirateBayRequest extends EngineRequest {
        public function get_request_url() {
            return "https://apibay.org/q.php?q=" . urlencode($this->query);
        }

        public function parse_results($response) {
            $results = array();
            $json_response = json_decode($response, true);

            if (empty($json_response))
            {
                return $results;
            }

            foreach ($json_response as $response)
            {
                $size = human_filesize($response["size"]);
                $hash = $response["info_hash"]; 
                $name = $response["name"];
                $seeders = (int) $response["seeders"];
                $leechers = (int) $response["leechers"];

                $magnet = "magnet:?xt=urn:btih:$hash&dn=$name" . $this->opts->bittorrent_trackers;

                if ($name == "No results returned")
                    break;

                array_push($results,
                    array (
                        "size" => htmlspecialchars($size),
                        "name" => htmlspecialchars($name),
                        "seeders" => (int) htmlspecialchars($seeders),
                        "leechers" => (int) htmlspecialchars($leechers),
                        "magnet" => htmlspecialchars($magnet),
                        "source" => "thepiratebay.org"
                    )
                );
            }

            return $results;

        }
    }
?>
