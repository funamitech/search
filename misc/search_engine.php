<?php
    abstract class EngineRequest {
        function __construct($opts, $mh) {
            $this->query = $opts->query;
            $this->page = $opts->page;
            $this->opts = $opts;

            $url = $this->get_request_url();
            error_log($url);
            if ($url) {
                $this->ch = curl_init($url);

                if ($opts->curl_settings)
                    curl_setopt_array($this->ch, $opts->curl_settings);

                if ($mh)
                    curl_multi_add_handle($mh, $this->ch);
            }
        }

        public function get_request_url(){
            return "";
        }
        public function successful() {
            return curl_getinfo($this->ch)['http_code'] == '200';
        }

        abstract function get_results();
        static public function print_results($results){}
    }

    function load_opts() {
        $opts = require "config.php";

        $opts->query = trim($_REQUEST["q"] ?? "");
        $opts->type = (int) ($_REQUEST["t"] ?? 0);
        $opts->page = (int) ($_REQUEST["p"] ?? 0);
        $opts->do_fallback = (int) ($_REQUEST["nfb"] ?? 0) == 0;

        $opts->theme = trim(htmlspecialchars($_COOKIE["theme"] ?? "dark"));

        $opts->safe_search = (int) ($_REQUEST["safe"] ?? 0) == 1 || isset($_COOKIE["safe_search"]);

        $opts->disable_special = (int) ($_REQUEST["ns"] ?? 0) == 1 || isset($_COOKIE["disable_special"]);

        $opts->disable_frontends = (int) ($_REQUEST["nf"] ?? 0) == 1 || isset($_COOKIE["disable_frontends"]);

        $opts->language = $_REQUEST["lang"] ?? trim(htmlspecialchars($_COOKIE["language"] ?? ""));

        $opts->do_fallback = (int) ($_REQUEST["nfb"] ?? 0) == 0;
        if (!$opts->instance_fallback) {
            $opts->do_fallback = false;
        }

        $opts->number_of_results ??= trim(htmlspecialchars($_COOKIE["number_of_results"]));

        foreach (array_keys($opts->frontends ?? array()) as $frontend) {
            $opts->frontends[$frontend]["instance_url"] = $_COOKIE[$frontend] ?? "";
        }
        return $opts;
    }

    function opts_to_params($opts) {
        $query = urlencode($opts->query);

        $params = "";
        $params .= "p=$opts->page";
        $params .= "&q=$query";
        $params .= "&t=$opts->type";
        $params .= "&nfb=" . ($opts->do_fallback ? 0 : 1);
        $params .= "&safe=" . ($opts->safe_search ? 1 : 0);
        $params .= "&nf=" . ($opts->disable_frontends ? 1 : 0);
        $params .= "&ns=" . ($opts->disable_special ? 1 : 0);

        return $params;
    }

    function init_search($opts, $mh) {
        switch ($opts->type)
        {
            case 1:
                require "engines/qwant/image.php";
                return new QwantImageSearch($opts, $mh);

            case 2:
                require "engines/invidious/video.php";
                return new VideoSearch($opts, $mh);

            case 3:
                if ($opts->disable_bittorent_search) {
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    break;
                }

                require "engines/bittorrent/merge.php";
                return new TorrentSearch($opts, $mh);

            case 4:
                if ($opts->disable_hidden_service_search) {
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                    break;
                }
                require "engines/ahmia/hidden_service.php";
                return new TorSearch($opts, $mh);

            default:
                require "engines/text/text.php";
                return new TextSearch($opts, $mh);
        }
    }

    function fetch_search_results($opts, $do_print) {
        $start_time = microtime(true);
        $mh = curl_multi_init();
        $search_category = init_search($opts, $mh);

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        $results = $search_category->get_results();

        if (empty($results)) {
            require "engines/librex/fallback.php";
            $results = get_librex_results($opts);
        }

        if (!$do_print)
            return $results;

        print_elapsed_time($start_time);
        $search_category->print_results($results);

        return $results;
    }
?>
