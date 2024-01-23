<?php
    class BraveSearchRequest extends EngineRequest {
        public function get_request_url() {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));

            $results_language = $this->opts->language;
            $number_of_results = $this->opts->number_of_results;

            // TODO find the right parameters for the url
            $url = "https://search.brave.com/search?q=$query_encoded&nfpr=1&spellcheck=0&start=$this->page";

            if (3 > strlen($results_language) && 0 < strlen($results_language)) {
                $url .= "&lr=lang_$results_language";
                $url .= "&hl=$results_language";
            }

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

            foreach($xpath->query("//div[@id='results']//div[contains(@class, 'snippet')]") as $result) {
                $url = $xpath->evaluate(".//a[contains(@class, 'h')]//@href", $result)[0];

                if ($url == null)
                    continue;

                $url = $url->textContent;

                if (!empty($results) && array_key_exists("url", $results) && end($results)["url"] == $url->textContent)
                    continue;

                $title = $xpath->evaluate(".//a[contains(@class, 'h')]//div[contains(@class, 'url')]", $result)[0];

                if ($title == null)
                    continue;
                $title = $title->textContent;

                $description = $xpath->evaluate(".//div[contains(@class, 'snippet-content')]//div[contains(@class, 'snippet-description')]", $result)[0]->textContent;

                array_push($results,
                    array (
                        "title" => htmlspecialchars($title),
                        "url" =>  htmlspecialchars($url),
                        // base_url is to be removed in the future, see #47
                        "base_url" => htmlspecialchars(get_base_url($url)),
                        "description" =>  $description == null ?
                                          TEXTS["result_no_description"] :
                                          htmlspecialchars($description)
                    )
                );

            }
           return $results;
        }

    }
?>
