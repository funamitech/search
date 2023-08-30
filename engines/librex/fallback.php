<?php

    class LibreXFallback extends EngineRequest {
        public function __construct($instance, $opts, $mh) {
            $this->instance = $instance;
            parent::__construct($opts, $mh);
        }

        public function get_request_url() {
           return $this->instance . "api.php?" . opts_to_params($this->opts);
        }

        public function get_results() {
            $response = curl_exec($this->ch);
            $response = json_decode($response, true);
            if (!$response)
                return array();

            return array_values($response);
        }
    }

    function load_instances($cooldowns) {
        $instances_json = json_decode(file_get_contents("instances.json"), true);

        if (empty($instances_json["instances"]))
            return array();

        $instances = array_map(fn($n) => $n['clearnet'], array_filter($instances_json['instances'], fn($n) => !is_null($n['clearnet'])));
        $instances = array_filter($instances, fn($n) => !has_cooldown($n, $cooldowns));
        shuffle($instances);
        return $instances;
    }

    function get_librex_results($opts) {
        if (!$opts->do_fallback)
            return array();

        $cooldowns = $opts->cooldowns;
        $instances = load_instances($cooldowns);

        $results = array();
        $tries = 0;

        do {
            $tries++;

            $instance = array_pop($instances);

            if (parse_url($instance)["host"] == parse_url($_SERVER['HTTP_HOST'])["host"])
                continue;

            $librex_request = new LibreXFallback($instance, $opts, null);
            $results = $librex_request->get_results();

            if (count($results) > 1)
                return $results;

            // on fail then do this
            $timeout = ($opts->request_cooldown ?? "1") * 60;
            $cooldowns = set_cooldown($instance, $timeout, $cooldowns);

        } while (!empty($instances));

        return array();
    }

?>
