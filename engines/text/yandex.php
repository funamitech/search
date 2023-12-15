<?php
    class YandexSearchRequest extends EngineRequest {
        public function get_request_url() {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));

            $results_language = $this->opts->language;
            $number_of_results = $this->opts->number_of_results;

            $url = "https://yandex.com/search?text=$query_encoded&nfpr=1&p=$this->page";

            if (!is_null($results_language))
                $url .= "&lang$results_language";

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            $r = $xpath->query("//ul[@id='search-result']");
            if (empty($r)) {
                return array("error" => array(
                    "message" => TEXTS["failure_empty"]
                ));
            }

            foreach($xpath->query("//li[contains(@class, 'serp-item')]") as $result) {
                $url = $xpath->evaluate(".//div//div//a[contains(@class, 'link')]//@href", $result)[0];

                if ($url == null)
                    continue;

                $url = $url->textContent;

                if (!empty($results) && array_key_exists("url", $results) && end($results)["url"] == $url->textContent)
                    continue;

                $title = $xpath->evaluate(".//div//div//a[contains(@class, 'link')]//h2[contains(@class, 'OrganicTitle-LinkText')]//span", $result)[0];

                if ($title == null)
                    continue;

                $title = $title->textContent;

                $description = $xpath->evaluate(".//div[contains(@class, 'Organic-ContentWrapper')]//div[contains(@class, 'text-container')]//span", $result)[0]->textContent;

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
