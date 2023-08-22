<?php
    require "engines/text/text.php";

    class TorSearch extends EngineRequest {
        public function get_request_url() {
            return "https://ahmia.fi/search/?q=" . urlencode($this->query);
        }

        public function get_results() {
            $response = curl_multi_getcontent($this->ch);
            $xpath = get_xpath($response);

            $results = array();

            foreach($xpath->query("//ol[@class='searchResults']//li[@class='result']") as $result)
            {
                $url = "http://" . $xpath->evaluate(".//cite", $result)[0]->textContent;
                $title = remove_special($xpath->evaluate(".//h4", $result)[0]->textContent);
                $description = $xpath->evaluate(".//p", $result)[0]->textContent;

                array_push($results,
                    array (
                        "title" => $title ? htmlspecialchars($title) : "No description provided",
                        "url" =>  htmlspecialchars($url),
                        "base_url" => htmlspecialchars(get_base_url($url)),
                        "description" => htmlspecialchars($description)
                    )
                );
            }

            return $results;
        }

        public static function print_results($results) {
            TextSearch::print_results($results);
        }
    }
?>
