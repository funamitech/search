<?php require "misc/header.php"; ?>

    <title>LibreY</title>
    </head>
    <body>
        <form class="search-container" action="search.php" method="get" autocomplete="off">
                <h1>Libre<span class="Y">Y</span></h1>
                <input type="text" name="q" autofocus/>
                <input type="hidden" name="p" value="0"/>
                <input type="hidden" name="t" value="0"/>
                <input type="submit" class="hide"/>
                <div class="search-button-wrapper">
                    <button name="t" value="0" type="submit">Search with LibreY</button>
                    <button name="t" value="3" type="submit">Search torrents with LibreY</button>
                </div>
        </form>

<?php require "misc/footer.php"; ?>
