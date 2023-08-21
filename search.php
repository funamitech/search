<?php 
    require "misc/header.php";

    $config = require "config.php";
    require "misc/tools.php";
    $query = trim($_REQUEST["q"]);

    abstract class EngineRequest {
        function __construct($query, $page, $mh, $config) {
            $this->query = $query;
            $this->page = $page;
            $this->config = $config;

            $url = $this->get_request_url();
            if ($url) {
                error_log($url);
                $this->ch = curl_init($url);
                curl_setopt_array($this->ch, $config->curl_settings);
                curl_multi_add_handle($mh, $this->ch);
            }
        }

        abstract function get_results();
        public function get_request_url(){
            return "";
        }
        public function print_results($results){}
    }

    function init_search($type, $query, $page, $mh, $config) {
        switch ($type)
        {
            case 0:
                require "engines/text.php";
                return new TextSearch($query, $page, $mh, $config);

            case 1:
                require "engines/qwant/image.php";
                return new QwantImageSearch($query, $page, $mh, $config);

            case 2:
                require "engines/invidious/video.php";
                return new VideoSearch($query, $page, $mh, $config);
                break;

            case 3:
                if ($config->disable_bittorent_search)
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                else
                    require "engines/bittorrent/merge.php";
                break;

            case 4:
                if ($config->disable_hidden_service_search)
                    echo "<p class=\"text-result-container\">The host disabled this feature! :C</p>";
                else
                    require "engines/ahmia/hidden_service.php";
                break;

            default:
                require "engines/text.php";
                break;
        }
    }
?>

<title>
<?php
  echo $query;
?> - LibreY</title>
</head>
    <body>
        <form class="sub-search-container" method="get" autocomplete="off">
            <h1 class="logomobile"><a class="no-decoration" href="./">Libre<span class="Y">Y</span></a></h1>
            <input type="text" name="q"
                <?php
                    $query_encoded = urlencode($query);

                    if (1 > strlen($query) || strlen($query) > 256)
                    {
                        header("Location: ./");
                        die();
                    }

                    echo "value=\"" . htmlspecialchars($query) . "\"";
                ?>
            >
            <br>
            <?php
                $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;
                echo "<button class=\"hide\" name=\"t\" value=\"$type\"/></button>";
            ?>
            <button type="submit" class="hide"></button>
            <input type="hidden" name="p" value="0">
            <div class="sub-search-button-wrapper">
                <?php
                    $categories = array("general", "images", "videos", "torrents", "tor");

                    foreach ($categories as $category)
                    {
                        $category_index = array_search($category, $categories);

                        if (($config->disable_bittorent_search && $category_index == 3) ||
                            ($config->disable_hidden_service_search && $category_index ==4))
                        {
                            continue;
                        }

                        echo "<a " . (($category_index == $type) ? "class=\"active\" " : "") . "href=\"./search.php?q=" . $query . "&p=0&t=" . $category_index . "\"><img src=\"static/images/" . $category . "_result.png\" alt=\"" . $category . " result\" />" . ucfirst($category)  . "</a>";
                    }
                ?>
            </div>
        <hr>
        </form>

        <?php
            $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;
            $start_time = microtime(true);

            $mh = curl_multi_init();
            $search_category = init_search($type, $query, $page, $mh, $config);

            $running = null;

            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            $results = $search_category->get_results($query, $page);
            print_elapsed_time($start_time);
            $search_category->print_results($results);

            if (2 > $type)
            {
                echo "<div class=\"next-page-button-wrapper\">";

                    if ($page != 0)
                    {
                        print_next_page_button("&lt;&lt;", 0, $query, $type);
                        print_next_page_button("&lt;", $page - 10, $query, $type);
                    }

                    for ($i=$page / 10; $page / 10 + 10 > $i; $i++)
                        print_next_page_button($i + 1, $i * 10, $query, $type);

                    print_next_page_button("&gt;", $page + 10, $query, $type);

                echo "</div>";
            }
        ?>

<?php require "misc/footer.php"; ?>
