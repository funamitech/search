<?php
    return (object) array(

        // e.g.: fr -> https://google.fr/
        "google_domain" => "com",

        // Google results will be in this language
        "google_language" => "en",

        // You can use any Invidious instance here
        "invidious_instance_for_video_results" => "https://invidious.namazso.eu",

        "disable_bittorent_search" => false,
        "bittorent_trackers" => "&tr=http%3A%2F%2Fnyaa.tracker.wf%3A7777%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.torrent.eu.org%3A451%2Fannounce",

        "disable_hidden_service_search" => false,

        /*
            Preset privacy friendly frontends for users, these can be overwritten by users in settings
            e.g.: "invidious" => "https://yewtu.be",
        */
        "invidious" => "https://yt.funami.tech",
        "bibliogram" => "",
        "nitter" => "https://twt.funami.tech",
        "libreddit" => "https://rd.funami.tech",
        "proxitok" => "",
        "wikiless" => "https://wikiless.funami.tech",
        "rimgo" => "", // imgur
        "scribe" => "", // medium
        "librarian" => "", // odysee
        "gothub" => "", // github
        "quetre" => "", // quora
        "libremdb" => "", // imdb,
        "breezewiki" => "", // fandom,
        "anonymousoverflow" => "", // stackoverflow

        /*
            To send requests trough a proxy uncomment CURLOPT_PROXY and CURLOPT_PROXYTYPE:

            CURLOPT_PROXYTYPE options:

                CURLPROXY_HTTP
                CURLPROXY_SOCKS4
                CURLPROXY_SOCKS4A
                CURLPROXY_SOCKS5
                CURLPROXY_SOCKS5_HOSTNAME

            !!! ONLY CHANGE THE OTHER OPTIONS IF YOU KNOW WHAT YOU ARE DOING !!!
        */
        "curl_settings" => array(
            // CURLOPT_PROXY => "ip:port",
            // CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 18,
            CURLOPT_VERBOSE => false
        )

    );
?>
