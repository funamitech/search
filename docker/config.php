<?php
    return (object) array(

        "google_domain" => "${CONFIG_GOOGLE_DOMAIN}",
        "language" => "${CONFIG_LANGUAGE}",
        "number_of_results" => ${CONFIG_NUMBER_OF_RESULTS},
        "invidious_instance_for_video_results" => "${CONFIG_INVIDIOUS_INSTANCE}",
        "disable_bittorent_search" => ${CONFIG_DISABLE_BITTORRENT_SEARCH},
        "bittorent_trackers" => "${CONFIG_BITTORRENT_TRACKERS}",
        "disable_hidden_service_search" => ${CONFIG_HIDDEN_SERVICE_SEARCH},
        "instance_fallback" => ${CONFIG_INSTANCE_FALLBACK},
        "request_cooldown" => ${CONFIG_RATE_LIMIT_COOLDOWN},
        "cache_time" => ${CONFIG_CACHE_TIME},
        "disable_api" => ${CONFIG_DISABLE_API},

        "frontends" => array(
            "invidious" => array(
                "instance_url" => "${APP_INVIDIOUS}",
                "project_url" => "https://docs.invidious.io/instances/",
                "original_name" => "YouTube",
                "original_url" => "youtube.com"
            ),
            "rimgo" => array(
                "instance_url" => "${APP_RIMGO}",
                "project_url" => "https://codeberg.org/video-prize-ranch/rimgo#instances",
                "original_name" => "Imgur",
                "original_url" => "imgur.com"
            ),
            "scribe" => array(
                "instance_url" => "${APP_SCRIBE}",
                "project_url" => "https://git.sr.ht/~edwardloveall/scribe/tree/main/docs/instances.md",
                "original_name" => "Medium",
                "original_url" => "medium.com"
            ),
            "gothub" => array(
                "instance_url" => "${APP_GOTHUB}",
                "project_url" => "https://codeberg.org/gothub/gothub#instances",
                "original_name" => "GitHub",
                "original_url" => "github.com"
            ),
            "nitter" => array(
                "instance_url" => "${APP_NITTER}",
                "project_url" => "https://github.com/zedeus/nitter/wiki/Instances",
                "original_name" => "Twitter",
                "original_url" => "twitter.com"
            ),
            "libreddit" => array(
                "instance_url" => "${APP_LIBREREDDIT}",
                "project_url" => "https://github.com/libreddit/libreddit-instances/blob/master/instances.md",
                "original_name" => "Reddit",
                "original_url" => "reddit.com"
            ),
            "proxitok" => array(
                "instance_url" => "${APP_PROXITOK}",
                "project_url" => "https://github.com/pablouser1/ProxiTok/wiki/Public-instances",
                "original_name" => "TikTok",
                "original_url" => "tiktok.com"
            ),
            "wikiless" => array(
                "instance_url" => "${APP_WIKILESS}",
                "project_url" => "https://github.com/Metastem/wikiless#instances",
                "original_name" => "Wikipedia",
                "original_url" => "wikipedia.org"
            ),
            "quetre" => array(
                "instance_url" => "${APP_QUETRE}",
                "project_url" => "https://github.com/zyachel/quetre#instances",
                "original_name" => "Quora",
                "original_url" => "quora.com"
            ),
            "libremdb" => array(
                "instance_url" => "${APP_LIBREMDB}",
                "project_url" => "https://github.com/zyachel/libremdb#instances",
                "original_name" => "IMDb",
                "original_url" => "imdb.com"
            ),
            "breezewiki" => array(
                "instance_url" => "${APP_BREEZEWIKI}",
                "project_url" => "https://docs.breezewiki.com/Links.html",
                "original_name" => "Fandom",
                "original_url" => "fandom.com"
            ),
            "anonymousoverflow" => array(
                "instance_url" => "${APP_ANONYMOUS_OVERFLOW}",
                "project_url" => "https://github.com/httpjamesm/AnonymousOverflow#clearnet-instances",
                "original_name" => "StackOverflow",
                "original_url" => "stackoverflow.com"
            ),
            "suds" => array(
                "instance_url" => "${APP_SUDS}",
                "project_url" => "https://git.vern.cc/cobra/Suds/src/branch/main/instances.json",
                "original_name" => "Snopes",
                "original_url" => "snopes.com"
            ),
            "biblioreads" => array(
                "instance_url" => "${APP_BIBLIOREADS}",
                "project_url" => "https://github.com/nesaku/BiblioReads#instances",
                "original_name" => "Goodreads",
                "original_url" => "goodreads.com"
            )
        ),


        "preferred_engines" => array(
            "text" => "${CONFIG_TEXT_SEARCH_ENGINE}"
        ),

        "curl_settings" => array(
            CURLOPT_PROXY => "${CURLOPT_PROXY}",
            CURLOPT_PROXYTYPE => ${CURLOPT_PROXYTYPE},
            CURLOPT_RETURNTRANSFER => ${CURLOPT_RETURNTRANSFER},
            CURLOPT_ENCODING => "${CURLOPT_ENCODING}",
            CURLOPT_USERAGENT => "${CURLOPT_USERAGENT}",
            CURLOPT_IPRESOLVE => ${CURLOPT_IPRESOLVE},
            CURLOPT_CUSTOMREQUEST => "${CURLOPT_CUSTOMREQUEST}",
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
            CURLOPT_MAXREDIRS => ${CURLOPT_MAXREDIRS},
            CURLOPT_TIMEOUT => ${CURLOPT_TIMEOUT},
            CURLOPT_VERBOSE => ${CURLOPT_VERBOSE},
            CURLOPT_FOLLOWLOCATION => ${CURLOPT_FOLLOWLOCATION}
        )
    );
?>
