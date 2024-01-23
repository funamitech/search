<div class="footer-container">
    <a href="/">YuruSearch</a>
    <a href="./instances.php" target="_blank">Instances</a>
    <a href="/settings.php">Settings</a>
    <a href="/api.php" target="_blank">API</a>
    <a href="/donate.php">Donate ❤️</a>
    <a href="https://github.com/funamitech/search">Powered by LibreY, a fork of LibreX</a>
</div>
<div class="git-container">
    <?php

        if (file_exists(".git/refs/heads/main")) {
          $hash = file_get_contents(".git/refs/heads/main");
        }

        echo "<a href='https://github.com/funamitech/search/commit/$hash' target='_blank'>" . printftext("latest_commit", $hash) . "</a>";
    ?>
</div>
</body>
</html>
