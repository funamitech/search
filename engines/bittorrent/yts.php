<?php
    class YTSRequest extends EngineRequest {
        public function get_request_url() {
            return "https://yts.mx/api/v2/list_movies.json?query_term=" . urlencode($this->query);
        }

        public function get_results() {
            $response = curl_multi_getcontent($this->ch);
            global $config;
            $results = array();
            $json_response = json_decode($response, true);

            if ($json_response["status"] != "ok" || $json_response["data"]["movie_count"] == 0)
                return $results;

            foreach ($json_response["data"]["movies"] as $movie)
            {
                    $name = $movie["title"];
                    $name_encoded = urlencode($name);

                    foreach ($movie["torrents"] as $torrent)
                    {

                        $hash = $torrent["hash"];
                        $seeders = $torrent["seeds"];
                        $leechers = $torrent["peers"];
                        $size = $torrent["size"];

                        $magnet = "magnet:?xt=urn:btih:$hash&dn=$name_encoded$this->opts->bittorrent_trackers";

                        array_push($results,
                            array (
                                "size" => htmlspecialchars($size),
                                "name" => htmlspecialchars($name),
                                "seeders" => htmlspecialchars($seeders),
                                "leechers" => htmlspecialchars($leechers),
                                "magnet" => htmlspecialchars($magnet),
                                "source" => "yts.mx"
                            )
                        );
                    }
            }

            return $results;
        }
    }
?>
