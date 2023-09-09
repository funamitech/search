<?php
    class WikipediaRequest extends EngineRequest {
        public function get_request_url() {
                $this->wikipedia_domain = "wikipedia.org";
                $query_encoded = urlencode($this->query);

                $languages = json_decode(file_get_contents("static/misc/languages.json"), true);

                if (array_key_exists($this->opts->language, $languages))
                    $this->wikipedia_domain = $languages[$this->opts->language]["wikipedia"] . ".wikipedia.org";

                return "https://$this->wikipedia_domain/w/api.php?format=json&action=query&prop=extracts%7Cpageimages&exintro&explaintext&redirects=1&pithumbsize=500&titles=$query_encoded";
        }

        public function parse_results($response) {
            $json_response = json_decode($response, true);

            $first_page = array_values($json_response["query"]["pages"])[0];

            if (array_key_exists("missing", $first_page))
                return array();

            $description = substr($first_page["extract"], 0, 250) . "...";

            $source = "https://$this->wikipedia_domain/wiki/$this->query";
            $response = array(
                "special_response" => array(
                    "response" => htmlspecialchars($description),
                    "source" => $source
                )
            );

            if (array_key_exists("thumbnail",  $first_page)) {
                $image_url = $first_page["thumbnail"]["source"];
                $response["special_response"]["image"] = $image_url;
            }

            return $response;
        }
    }
?>
