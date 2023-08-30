<?php
    require "misc/cooldowns.php";
    abstract class EngineRequest {
        protected $DO_CACHING = true;
        function __construct($opts, $mh) {
            $this->query = $opts->query;
            $this->page = $opts->page;
            $this->mh = $mh;
            $this->opts = $opts;

            $this->url = $this->get_request_url();
            if (!$this->url)
                return;

            if (has_cached_results($this->url))
                return;

            $this->ch = curl_init($this->url);

            if ($opts->curl_settings)
                curl_setopt_array($this->ch, $opts->curl_settings);

            if ($mh)
                curl_multi_add_handle($mh, $this->ch);
        }

        public function get_request_url() {
            return "";
        }

        public function successful() {
            return (isset($this->ch) && curl_getinfo($this->ch)['http_code'] == '200') 
                || has_cached_results($this->url);
        }

        abstract function parse_results($response);

        public function get_results() {
            if (!isset($this->url))
                return $this->parse_results(null);

            if ($this->DO_CACHING && has_cached_results($this->url))
                return fetch_cached_results($this->url);

            if (!isset($this->ch))
                return $this->parse_results(null);

            $response = $this->mh ? curl_multi_getcontent($this->ch) : curl_exec($this->ch);
            $results = $this->parse_results($response) ?? array();

            if ($this->DO_CACHING && !empty($results))
                store_cached_results($this->url, $results, $this->opts->cache_time * 60);

            return $results;
        }

        static public function print_results($results){}
    }

    function load_opts() {
        $opts = require "config.php";

        $opts->request_cooldown ??= 25;
        $opts->cache_time ??= 25;

        $opts->query = trim($_REQUEST["q"] ?? "");
        $opts->type = (int) ($_REQUEST["t"] ?? 0);
        $opts->page = (int) ($_REQUEST["p"] ?? 0);

        $opts->theme = $_REQUEST["theme"] ?? trim(htmlspecialchars($_COOKIE["theme"] ?? "dark"));

        $opts->safe_search = (int) ($_REQUEST["safe"] ?? 0) == 1 || isset($_COOKIE["safe_search"]);

        $opts->disable_special = (int) ($_REQUEST["ns"] ?? 0) == 1 || isset($_COOKIE["disable_special"]);

        $opts->disable_frontends = (int) ($_REQUEST["nf"] ?? 0) == 1 || isset($_COOKIE["disable_frontends"]);

        $opts->language = $_REQUEST["lang"] ?? trim(htmlspecialchars($_COOKIE["language"] ?? $opts->language));

        $opts->do_fallback = (int) ($_REQUEST["nfb"] ?? 0) == 0;
        if (!$opts->instance_fallback) {
            $opts->do_fallback = false;
        }

        $opts->number_of_results ??= trim(htmlspecialchars($_COOKIE["number_of_results"]));

        foreach (array_keys($opts->frontends ?? array()) as $frontend) {
            $opts->frontends[$frontend]["instance_url"] = $_COOKIE[$frontend] ?? $opts->frontends[$frontend]["instance_url"];
        }

        $opts->curl_settings[CURLOPT_FOLLOWLOCATION] ??= true;

        return $opts;
    }

    function opts_to_params($opts) {
        $query = urlencode($opts->query);

        $params = "";
        $params .= "p=$opts->page";
        $params .= "&q=$query";
        $params .= "&t=$opts->type";
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
        $opts->cooldowns = load_cooldowns();

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
