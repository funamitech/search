<?php
    class DuckDuckGoRequest extends EngineRequest {
        function get_request_url()
        {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));
            $results = array();

            $domain = 'com';
            $results_language = $this->opts->language;
            $number_of_results = $this->opts->number_of_results;

            $url = "https://html.duckduckgo.$domain/html/?q=$query_encoded&kd=-1&s=" . 3 * $this->page;

            if (3 > strlen($results_language) && 0 < strlen($results_language))
                $url .= "&lr=lang_$results_language";

            if (3 > strlen($number_of_results) && 0 < strlen($number_of_results))
                $url .= "&num=$number_of_results";

            if (isset($_COOKIE["safe_search"]))
                $url .= "&safe=medium";

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;
            
            foreach($xpath->query("/html/body/div[1]/div[". count($xpath->query('/html/body/div[1]/div')) ."]/div/div/div[contains(@class, 'web-result')]/div") as $result) {
                $url = $xpath->evaluate(".//h2[@class='result__title']//a/@href", $result)[0];
                
                if ($url == null)
                    continue;

                if (!empty($results)) { // filter duplicate results
                    if (end($results)["url"] == $url->textContent)
                        continue;
                }

                $url = $url->textContent;

                $title = $xpath->evaluate(".//h2[@class='result__title']", $result)[0];
                $description = $xpath->evaluate(".//a[@class='result__snippet']", $result)[0];

                array_push($results,
                    array (
                        "title" => htmlspecialchars($title->textContent),
                        "url" =>  htmlspecialchars($url),
                        // base_url is to be removed in the future, see #47
                        "base_url" => htmlspecialchars(get_base_url($url)),
                        "description" =>  $description == null ?
                                          "No description was provided for this site." :
                                          htmlspecialchars($description->textContent)
                    )
                );
           }
           return $results;
        }

    }
?>
