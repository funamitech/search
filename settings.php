<?php
        require "misc/search_engine.php";
        $opts = load_opts();

        // Reset all cookies when resetting, or before saving new cookies
        if (isset($_REQUEST["reset"]) || isset($_REQUEST["save"])) {
            if (isset($_SERVER["HTTP_COOKIE"])) {
                $cookies = explode(";", $_SERVER["HTTP_COOKIE"]);
                foreach($cookies as $cookie) {
                    $parts = explode("=", $cookie);
                    $name = trim($parts[0]);
                    setcookie($name, "", time() - 1000);
                }
            }
        }

        if (isset($_REQUEST["save"])) {
            foreach($_POST as $key=>$value) {
                if (!empty($value)) {
                    setcookie($key, $value, time() + (86400 * 90), '/');
                } else {
                    setcookie($key, "", time() - 1000);
                }
            }
        }

        if (isset($_REQUEST["save"]) || isset($_REQUEST["reset"])) {
            header("Location: ./");
            die();
        }

        require "misc/header.php";
?>

    <title>YuruSearch - Settings</title>
    </head>
    <body>
        <div class="misc-container">
            <h1>Settings</h1>
            <form method="post" enctype="multipart/form-data" autocomplete="off">
              <div>
                <label for="theme">Theme:</label>
                <select name="theme">
                <?php
                    $themes = "<option value=\"dark\">Dark</option>
                    <option value=\"darker\">Darker</option>
                    <option value=\"amoled\">AMOLED</option>
                    <option value=\"light\">Light</option>
                    <option value=\"auto\">Auto</option>
					<option value=\"dracula\">Dracula</option>
                    <option value=\"nord\">Nord</option>
                    <option value=\"night_owl\">Night Owl</option>
                    <option value=\"discord\">Discord</option>
                    <option value=\"google\">Google Dark</option>
                    <option value=\"startpage\">Startpage Dark</option>
                    <option value=\"gruvbox\">Gruvbox</option>
                    <option value=\"github_night\">GitHub Night</option>
                    <option value=\"catppuccin\">Catppucin</option>
                    <option value=\"ubuntu\">Ubuntu</option>
                    <option value=\"tokyo_night\">Tokyo night</option>";

                    if (isset($_COOKIE["theme"])) {
                        $theme = $opts->theme;
                        $themes = str_replace($theme . "\"", $theme . "\" selected", $themes);
                    }

                    echo $themes;
                ?>
                </select>
                </div>
                <div>
                    <label>Disable special queries (e.g.: currency conversion)</label>
                    <input type="checkbox" name="disable_special" <?php echo $opts->disable_special ? "checked"  : ""; ?> >
                </div>

                <h2>Privacy friendly frontends</h2>
                <p>For an example if you want to view YouTube without getting spied on, click on "Invidious", find the instance that is most suitable for you then paste it in (correct format: https://example.com)</p>
                <div class="settings-textbox-container">
                      <?php
                           foreach($opts->frontends as $frontend => $data)
                           {
                                echo "<div>";
                                echo "<a for=\"$frontend\" href=\"" . $data["project_url"] . "\" target=\"_blank\">" . ucfirst($frontend) . "</a>";
                                echo "<input type=\"text\" name=\"$frontend\" placeholder=\"Replace " .  $data["original_name"] . "\" value=";
                                echo htmlspecialchars($opts->frontends["$frontend"]["instance_url"] ?? "");
                                echo ">";
                                echo "</div>";
                           }
                      ?>
                </div>
                <div>
                    <label>Disable frontends</label>
                    <input type="checkbox" name="disable_frontends" <?php echo $opts->disable_frontends ? "checked"  : ""; ?> >
                </div>

                <h2>Search settings</h2>
                <div class="settings-textbox-container">
                    <div>
                        <span>Language</span>
                        <?php
                            // TODO make this a dropdown
                            echo "<input type=\"text\" name=\"language\" placeholder=\"any\" value=\"" . htmlspecialchars($opts->language ?? "") . "\">";
                        ?>
                    </div>
                    <div>
                        <label>Number of results per page</label>
                        <input type="number" name="number_of_results" value="<?php echo htmlspecialchars($opts->number_of_results ?? "10") ?>" >
                    </div>
                </div>
                <div>
                    <label>Safe search</label>
                    <input type="checkbox" name="safe_search" <?php echo $opts->safe_search ? "checked"  : ""; ?> >
                </div>

                <div>
                  <button type="submit" name="save" value="1">Save</button>
                  <button type="submit" name="reset" value="1">Reset</button>
                </div>
            </form>
        </div>

<?php require "misc/footer.php"; ?>
