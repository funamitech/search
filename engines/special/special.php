<?php

    function check_for_special_search($query) {
        if (isset($_COOKIE["disable_special"]))
            return 0;

         $query_lower = strtolower($query);
         $split_query = explode(" ", $query);

         if (strpos($query_lower, "to") && count($split_query) >= 4) // currency
         {
            $amount_to_convert = floatval($split_query[0]);
            if ($amount_to_convert != 0)
                return 1;
         }
         else if (strpos($query_lower, "mean") && count($split_query) >= 2) // definition
         {
             return 2;
         }
         else if (strpos($query_lower, "my") !== false)
         {
            if (strpos($query_lower, "ip"))
            {
                return 3;
            }
            else if (strpos($query_lower, "user agent") || strpos($query_lower, "ua"))
            {
                return 4;
            }
         }
         else if (strpos($query_lower, "weather") !== false)
         {
                return 5;
         }
         else if ($query_lower == "tor")
         {
                return 6;
         }
         else if (3 > count(explode(" ", $query))) // wikipedia
         {
             return 7;
         }

        return 0;
    }

    function get_special_search_request ($query, $page, $mh, $config) {
        $special_search = $page ? 0 : check_for_special_search($query);
        $special_request = null;
        $url = null;

        if ($special_search == 0)
            return null;

        switch ($special_search) {
            case 1:
                require "engines/special/currency.php";
                $special_request = new CurrencyRequest($query, $page, $mh, $config);
                break;
            case 2:
                require "engines/special/definition.php";
                $special_request = new DefinitionRequest($query, $page, $mh, $config);
                break;
            case 3:
                require "engines/special/ip.php";
                $special_request = new IPRequest($query, $page, $mh, $config);
                break;
            case 4:
                require "engines/special/user_agent.php";
                $special_request = new UserAgentRequest($query, $page, $mh, $config);
                break;
            case 5:
                require "engines/special/weather.php";
                $special_request = new WeatherRequest($query, $page, $mh, $config);
                break;
            case 6:
                require "engines/special/tor.php";
                $special_request = new TorRequest($query, $page, $mh, $config);
                break;
            case 7:
                require "engines/special/wikipedia.php";
                $special_request = new WikipediaRequest($query, $page, $mh, $config);
                break;
        }

        return $special_request;
    }
?>
