<?php

class LibreXFallback extends EngineRequest {
    public function __construct($instance, $opts, $mh) {
        $this->instance = $instance;
        parent::__construct($opts, $mh);
    }

    public function get_request_url() {
       return $instance . "api.php?" .opts_to_params($opts);
    }

}


    function get_librex_results($opts, $mh) {
        global $config;

        if (isset($_REQUEST["nfb"]) && $_REQUEST["nfb"] == "1")
            return array();

        if (!$config->instance_fallback) 
            return array();

        $instances_json = json_decode(file_get_contents("instances.json"), true);

        if (empty($instances_json["instances"]))
            return array();


        $instances = array_map(fn($n) => $n['clearnet'], array_filter($instances_json['instances'], fn($n) => !is_null($n['clearnet'])));
        shuffle($instances);

        $query_encoded = urlencode($query);

        $results = array();
        $tries = 0;

        do {
            $tries++;

            $instance = array_pop($instances);

            if (parse_url($instance)["host"] == parse_url($_SERVER['HTTP_HOST'])["host"])
                continue;

            $url = $instance . "api.php?q=$query_encoded&p=$page&t=0&nfb=1";

            $librex_ch = curl_init($url);
            curl_setopt_array($librex_ch, $config->curl_settings);
            copy_cookies($librex_ch);
            $response = curl_exec($librex_ch);
            curl_close($librex_ch);

            $code = curl_getinfo($librex_ch)["http_code"];
            $results = json_decode($response, true);

        } while ( !empty($instances) && ($results == null || count($results) <= 1));

        if (empty($instances))
            return array();

        return array_values($results);
    }

?>
