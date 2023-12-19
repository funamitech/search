<div class="footer-container">
    <a href="./">LibreY</a>
    <a href="https://github.com/Ahwxorg/librey/" target="_blank"><?php printtext("source_code_link");?></a>
    <a href="./instances.php" target="_blank"><?php printtext("instances_link");?></a>
    <a href="./settings.php"><?php printtext("settings_link");?></a>
    <a href="./api.php" target="_blank"><?php printtext("api_link");?></a>
    <a href="./donate.php"><?php printtext("donate_link");?></a>
</div>
<div class="git-container">
    <?php
        $hash = file_get_contents(".git/refs/heads/main");
        echo "<a href=\"https://github.com/Ahwxorg/librey/commit/$hash\" target=\"_blank\">" . printftext("latest_commit", $hash) . "</a>";
    ?>
</div>
</body>
</html>
