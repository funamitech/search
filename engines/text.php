<?php
    class TextSearch extends EngineRequest {
        public function __construct($query, $page, $mh, $config) {
            $this->query = $query;
            $this->page = $page;

            $engine=$config->preferred_engines['text'];
            if (is_null($engine))
                $engine = "google";
            $query_parts = explode(" ", $query);
            $last_word_query = end($query_parts);
            if (substr($query, 0, 1) == "!" || substr($last_word_query, 0, 1) == "!")
                check_ddg_bang($query);

            if ($engine == "google") {
                require "engines/google/text.php";
                $this->engine_request = new GoogleRequest($query,  $page, $mh, $config);
            }

            if ($engine == "duckduckgo") {
                require "engines/duckduckgo/text.php";
                $this->engine_request = new DuckDuckGoRequest($query, $page, $mh, $config);
            }

            require "engines/special.php";
            $this->special_request = get_special_search_request($query, $page, $mh, $config);
        }

        public function get_results() {
            if (curl_getinfo($this->engine_request->ch)['http_code'] != '200') 
            {
                require "engines/librex/text.php";
                return get_librex_results($this->query, $this->page);
            }

            $results = $this->engine_request->get_results();

            $special_result = $this->special_request->get_results();
            if ($special_result)
                $results = array_merge(array($special_result), $results);

            return $results;
        }

        public static function print_results($results) {

            if (empty($results))
                return;

            $special = $results[0];

            if (array_key_exists("did_you_mean", $special)) 
            {
                $didyoumean = $special["did_you_mean"];
                $new_url = "/search.php?q="  . urlencode($didyoumean);
                echo "<p class=\"did-you-mean\">Did you mean ";
                echo "<a href=\"$new_url\">$didyoumean</a>";
                echo "?</p>";
            }

            if (array_key_exists("special_response", $special)) 
            {
                $response = $special["special_response"]["response"];
                $source = $special["special_response"]["source"];

                echo "<p class=\"special-result-container\">";
                if (array_key_exists("image", $special["special_response"]))
                {
                    $image_url = $special["special_response"]["image"];
                    echo "<img src=\"image_proxy.php?url=$image_url\">";
                }
                echo $response;
                if ($source)
                    echo "<a href=\"$source\" target=\"_blank\">$source</a>";
                echo "</p>";
            }

            echo "<div class=\"text-result-container\">";

            foreach($results as $result)
            {
                if (!array_key_exists("title", $result))
                    continue;

                $title = $result["title"];
                $url = $result["url"];
                $base_url = $result["base_url"];
                $description = $result["description"];

                echo "<div class=\"text-result-wrapper\">";
                echo "<a href=\"$url\">";
                echo "$base_url";
                echo "<h2>$title</h2>";
                echo "</a>";
                echo "<span>$description</span>";
                echo "</div>";
            }

            echo "</div>";
        }
    }

    function check_ddg_bang($query)
    {

        $bangs_json = file_get_contents("static/misc/ddg_bang.json");
        $bangs = json_decode($bangs_json, true);

        if (substr($query, 0, 1) == "!")
            $search_word = substr(explode(" ", $query)[0], 1);
        else
            $search_word = substr(end(explode(" ", $query)), 1);
        
        $bang_url = null;

        foreach($bangs as $bang)
        {
            if ($bang["t"] == $search_word)
            {
                $bang_url = $bang["u"];
                break;
            }
        }

        if ($bang_url)
        {
            $bang_query_array = explode("!" . $search_word, $query);
            $bang_query = trim(implode("", $bang_query_array));

            $request_url = str_replace("{{{s}}}", str_replace('%26quot%3B','%22', urlencode($bang_query)), $bang_url);
            $request_url = check_for_privacy_frontend($request_url);

            header("Location: " . $request_url);
            die();
        }
    }

?>
