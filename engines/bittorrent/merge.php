<?php
    class TorrentSearch extends EngineRequest {
        public function __construct($query, $page, $mh, $config) {
            $this->query = $query;
            $this->page = $page;

            require "engines/bittorrent/thepiratebay.php";
            require "engines/bittorrent/rutor.php";
            require "engines/bittorrent/yts.php";
            require "engines/bittorrent/torrentgalaxy.php";
            require "engines/bittorrent/1337x.php";
            require "engines/bittorrent/sukebei.php";

            $this->requests = array(
                new PirateBayRequest($query, $page, $mh, $config),
                new _1337xRequest($query, $page, $mh, $config),
                new NyaaRequest($query, $page, $mh, $config),
                new RutorRequest($query, $page, $mh, $config),
                new SukebeiRequest($query, $page, $mh, $config),
                new TorrentGalaxyRequest($query, $page, $mh, $config),
                new YTSRequest($query, $page, $mh, $config),
            );
        }

        public function get_results() {
            $query = urlencode($this->query);
            $results = array();
            foreach ($this->requests as $request) {
                error_log($request->get_request_url());
                error_log( curl_getinfo($request->ch)['http_code'] );
                if ($request->successful()) {
                    $results = array_merge($results, $request->get_results());
                }
            }

            $seeders = array_column($results, "seeders");
            array_multisort($seeders, SORT_DESC, $results);

            return $results; 
        }

        public static function print_results($results) {
            echo "<div class=\"text-result-container\">";

            if (empty($results)) {
                echo "<p>There are no results. Please try different keywords!</p>";
                return;
            }

            foreach($results as $result) {
                $source = $result["source"];
                $name = $result["name"];
                $magnet = $result["magnet"];
                $seeders = $result["seeders"];
                $leechers = $result["leechers"];
                $size = $result["size"];

                echo "<div class=\"text-result-wrapper\">";
                echo "<a href=\"$magnet\">";
                echo "$source";
                echo "<h2>$name</h2>";
                echo "</a>";
                echo "<span>SE: <span class=\"seeders\">$seeders</span> - ";
                echo "LE: <span class=\"leechers\">$leechers</span> - ";
                echo "$size</span>";
                echo "</div>";
            }

            echo "</div>";
        }
    }

?>
