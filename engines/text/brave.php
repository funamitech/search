<?php
    class BraveSearchRequest extends EngineRequest {
        public function get_request_url() {
            // TODO build url for brave search

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            // TODO implement brave search scraper

           return $results;
        }

    }
?>
