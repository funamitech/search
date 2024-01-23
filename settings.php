<?php
        require "misc/search_engine.php";

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

        $opts = load_opts();

        require "misc/header.php";
?>

    <title>YuruSearch - Settings</title>
    </head>
    <body>
        <div class="misc-container">
            <h1>Settings</h1>
            <form method="post" enctype="multipart/form-data" autocomplete="off">
              <div>
                <label for="theme"><?php printtext("settings_theme");?>:</label>
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

                    if (isset($opts->theme)) {
                        $theme = $opts->theme;
                        $themes = str_replace($theme . "\"", $theme . "\" selected", $themes);
                    }

                    echo $themes;
                ?>
                </select>
                </div>
                <div>
                    <label><?php printtext("settings_special_disabled");?></label>
                    <input type="checkbox" name="disable_special" <?php echo $opts->disable_special ? "checked"  : ""; ?> >
                </div>

                <h2><?php printtext("settings_frontends");?></h2>
                <p><?php printtext("settings_frontends_description");?></p>
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
                    <label><?php printtext("settings_frontends_disable");?></label>
                    <input type="checkbox" name="disable_frontends" <?php echo $opts->disable_frontends ? "checked"  : ""; ?> >
                </div>

                <h2><?php printtext("settings_search_settings");?></h2>
                <div class="settings-textbox-container">
                    <div>
                        <span><?php printtext("settings_preferred_engine");?></span>
                        <select name="engine">
                        <?php
                           require "engines/text/text.php";

                           $engines = get_engines();

                           $options = "";

                           $options .= "<option value=\"\" " . (!isset($opts->engine) ? "selected" : "") . ">auto</option>";

                           foreach ($engines as $engine) {
                               $selected = $opts->engine == $engine ? "selected" : "";
                               $options .= "<option value=\"$engine\" $selected>$engine</option>";
                           }
                           echo $options;
                        ?>
                        </select>
                    </div>
                    <div>
                        <label><?php printtext("settings_number_of_results");?></label>
                        <input type="number" name="number_of_results" value="<?php echo htmlspecialchars($opts->number_of_results ?? "10") ?>" >
                    </div>
                </div>
                <div class="settings-textbox-container">
                    <div>
                        <span><?php printtext("settings_language");?></span>
                        <select name="language">
                        <?php

                           $languages = json_decode(file_get_contents("static/misc/languages.json"), true);
                           $options = "";

                           $options .= "<option value=\"\" " . (!isset($opts->language) ? "selected" : "") . ">Any</option>";

                           foreach ($languages as $lang_code => $language) {
                               $name = $language["name"];
                               $selected = $opts->language == $lang_code ? "selected" : "";
                               $options .= "<option value=\"$lang_code\" $selected>$name</option>";
                           }
                           echo $options;
                        ?>
                        </select>
                    </div>
                    <div>
                        <label><?php printtext("settings_number_of_results");?></label>
                        <input type="number" name="number_of_results" value="<?php echo htmlspecialchars($opts->number_of_results ?? "10") ?>" >
                    </div>
                </div>
                <div>
                    <label><?php printtext("settings_safe_search");?></label>
                    <input type="checkbox" name="safe_search" <?php echo $opts->safe_search ? "checked"  : ""; ?> >
                </div>

                <div>
                  <button type="submit" name="save" value="1"><?php printtext("settings_save");?></button>
                  <button type="submit" name="reset" value="1"><?php printtext("settings_reset");?></button>
                </div>
            </form>
        </div>

<?php require "misc/footer.php"; ?>
