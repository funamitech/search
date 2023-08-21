<?php 
    require "misc/header.php";

    $config = require "config.php";
    require "misc/tools.php";
    require "misc/search_engine.php";

    function print_page_buttons($type, $query, $page) {
        if ($type > 1)
            return;
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

    $query = trim($_REQUEST["q"]);
    $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;
    $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;
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
            fetch_search_results($type, $query, $page, $config, true);
            print_page_buttons($type, $query, $page);
        ?>

<?php require "misc/footer.php"; ?>
