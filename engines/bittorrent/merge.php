<?php
    class TorrentSearch extends EngineRequest {
        public function __construct($opts, $mh) {
            parent::__construct($opts, $mh);

            require "engines/bittorrent/thepiratebay.php";
            require "engines/bittorrent/rutor.php";
            require "engines/bittorrent/yts.php";
            require "engines/bittorrent/torrentgalaxy.php";
            require "engines/bittorrent/1337x.php";
            require "engines/bittorrent/sukebei.php";

            $this->requests = array(
                new PirateBayRequest($opts, $mh),
                new _1337xRequest($opts, $mh),
                new NyaaRequest($opts, $mh),
                new RutorRequest($opts, $mh),
                new SukebeiRequest($opts, $mh),
                new TorrentGalaxyRequest($opts, $mh),
                new YTSRequest($opts, $mh),
            );
        }

        public function get_results() {
            $results = array();
            foreach ($this->requests as $request) {
                if ($request->successful())
                    $results = array_merge($results, $request->get_results());
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
