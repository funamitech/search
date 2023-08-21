<?php
    $config = require "config.php";
    require "misc/tools.php";
    require "misc/search_engine.php";

    if (!isset($_REQUEST["q"]))
    {
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

    $query = $_REQUEST["q"];
    $query_encoded = urlencode($query);
    $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;
    $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;

    $results = fetch_search_results($type, $query, $page, $config, false);
    header("Content-Type: application/json");
    echo json_encode($results);
?>
