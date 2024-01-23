<?php
    require "engines/text/text.php";

    class TorSearch extends EngineRequest {
        public function get_request_url() {
            return "https://ahmia.fi/search/?q=" . urlencode($this->query);
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            foreach($xpath->query("//ol[@class='searchResults']//li[@class='result']") as $result)
            {
                $url = "http://" . $xpath->evaluate(".//cite", $result)[0]->textContent;
                $title = remove_special($xpath->evaluate(".//h4", $result)[0]->textContent);
                $description = $xpath->evaluate(".//p", $result)[0]->textContent;

                array_push($results,
                    array (
                        "title" => $title ? htmlspecialchars($title) : TEXTS["result_no_description"],
                        "url" =>  htmlspecialchars($url),
                        // base_url is to be removed in the future, see #47
                        "base_url" => htmlspecialchars(get_base_url($url)),
                        "description" => htmlspecialchars($description)
                    )
                );
            }

            return $results;
        }

        public static function print_results($results, $opts) {
            TextSearch::print_results($results, $opts);
        }
    }
?>
