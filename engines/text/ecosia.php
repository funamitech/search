<?php
    class EcosiaSearchRequest extends EngineRequest {
        public function get_request_url() {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));

            $results_language = $this->opts->language;
            $number_of_results = $this->opts->number_of_results;

            // TODO figure out how to not autocorrect
            $url = "https://www.ecosia.org/search?method=index&q=$query_encoded&p=$this->page";

            if (!is_null($results_language))
                $url .= "&lang$results_language";

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;


            foreach($xpath->query("//div[contains(@class, 'mainline__result-wrapper')]") as $result) {
                $url = $xpath->evaluate(".//article//div[contains(@class, 'result__body')]//div[contains(@class, 'result__header')]//div[contains(@class, 'result__info')]//a[contains(@class, 'result__link')]//@href", $result)[0];

                if ($url == null)
                    continue;

                $url = $url->textContent;

                if (!empty($results) && array_key_exists("url", $results) && end($results)["url"] == $url->textContent)
                    continue;

                $title = $xpath->evaluate(".//article//div[contains(@class, 'result__body')]//div[contains(@class, 'result__header')]//div[contains(@class, 'result__title')]//a//h2", $result)[0];

                if ($title == null)
                    continue;

                $title = $title->textContent;

                $description = $xpath->evaluate(".//article//div[contains(@class, 'result__body')]//div[contains(@class, 'result__columns')]//div[contains(@class, 'result__columns-start')]//div//div//div/p", $result)[0]->textContent;

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
