<?php
    class WikipediaRequest extends EngineRequest {
        public function get_request_url() {
                $this->wikipedia_language = $this->opts->language;
                $query_encoded = urlencode($this->query);

                if (!in_array($this->wikipedia_language, json_decode(file_get_contents("static/misc/wikipedia_langs.json"), true)))
                    $this->wikipedia_language = "en";

                return "https://$this->wikipedia_language.wikipedia.org/w/api.php?format=json&action=query&prop=extracts%7Cpageimages&exintro&explaintext&redirects=1&pithumbsize=500&titles=$query_encoded";
        }

        public function parse_results($response) {
            $json_response = json_decode($response, true);

            $first_page = array_values($json_response["query"]["pages"])[0];

            if (array_key_exists("missing", $first_page))
                return array();

            $description = substr($first_page["extract"], 0, 250) . "...";

            $source = "https://$this->wikipedia_language.wikipedia.org/wiki/$this->query";
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
