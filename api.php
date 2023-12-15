<?php
    require "misc/search_engine.php";
    require "locale/localization.php";

    $opts = load_opts();
    if ($opts->disable_api) {
        echo "<p>" . TEXTS["api_unavailable"] . "</p>";
        die();
    }

    require "misc/tools.php";

    if (!$opts->query) {
        echo "<p>Example API request: <a href=\"./api.php?q=gentoo&p=2&t=0\">./api.php?q=gentoo&p=2&t=0</a></p>
        <br/>
        <p>\"q\" is the keyword</p>
        <p>\"p\" is the result page (the first page is 0)</p>
        <p>\"t\" is the search type (0=text, 1=image, 2=video, 3=torrent, 4=tor)</p>
        <br/>
        <p>The results are going to be in JSON format.</p>
        <p>The API supports both POST and GET requests.</p>";

        die();
    }

    $results = fetch_search_results($opts, false);
    header("Content-Type: application/json");
    echo json_encode($results);
?>
