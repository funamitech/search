<div class="footer-container">
    <a href="https://github.com/funamitech/search">Powered by LibreX</a>
    <a href="./settings.php">Settings</a>
    <a href="./api.php" target="_blank">API</a>
    <a href="https://github.com/sponsors/NoaHimesaka1873">Donate ❤️</a>
</div>
<div class="git-container">
    <?php
        $hash = file_get_contents(".git/refs/heads/main");
        echo "<a href=\"https://github.com/funamitech/search/commit/$hash\" target=\"_blank\">Latest commit: $hash</a>";
    ?>
</div>
</body>
</html>
