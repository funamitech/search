<?php require "locale/localization.php"; ?>
<!DOCTYPE html >
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8"/>
        <meta name="description" content="A privacy respecting meta search engine."/>
        <meta name="referrer" content="no-referrer"/>
        <link rel="stylesheet" type="text/css" href="static/css/styles.css"/>
        <link title="<?php printtext("page_title"); ?>" type="application/opensearchdescription+xml" href="opensearch.xml?method=POST" rel="search"/>
        <link rel="stylesheet" type="text/css" href="<?php
$theme = $_REQUEST["theme"] ?? trim(htmlspecialchars($_COOKIE["theme"] ?? "dark"));
                echo "static/css/" . $theme . ".css";
        ?>"/>
