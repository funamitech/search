<?php
    abstract class EngineRequest {
        function __construct($query, $page, $mh, $config) {
            $this->query = $query;
            $this->page = $page;
            $this->config = $config;

            $url = $this->get_request_url();
            if ($url) {
                $this->ch = curl_init($url);
                curl_setopt_array($this->ch, $config->curl_settings);
                curl_multi_add_handle($mh, $this->ch);
            }
        }

        public function get_request_url(){
            return "";
        }

        abstract function get_results();
        static public function print_results($results){}
    }

    function init_search($type, $query, $page, $mh, $config) {
        switch ($type)
        {
            case 1:
                require "engines/qwant/image.php";
                return new QwantImageSearch($query, $page, $mh, $config);

            case 2:
                require "engines/invidious/video.php";
                return new VideoSearch($query, $page, $mh, $config);

            case 3:
                if ($config->disable_bittorent_search) {
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    break;
                }

                require "engines/bittorrent/merge.php";
                return new TorrentSearch($query, $page, $mh, $config);

            case 4:
                if ($config->disable_hidden_service_search) {
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    break;
                }
                require "engines/ahmia/hidden_service.php";
                return new TorSearch($query, $page, $mh, $config);

            default:
                require "engines/text.php";
                return new TextSearch($query, $page, $mh, $config);
        }
    }

    function fetch_search_results($type, $query, $page, $config, $do_print) {
        $start_time = microtime(true);
        $mh = curl_multi_init();
        $search_category = init_search($type, $query, $page, $mh, $config);

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        $results = $search_category->get_results($query, $page);

        if (!$do_print)
            return $results;

        print_elapsed_time($start_time);
        $search_category->print_results($results);

        return $results;
    }
?>
