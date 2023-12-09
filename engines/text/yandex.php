<?php
    class YandexSearchRequest extends EngineRequest {
        public function get_request_url() {
            // TODO build url for yandex

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            // TODO implement yandex scraper

           return $results;
        }

    }
?>
