<?php 
    require "misc/header.php";
    require "misc/tools.php";
            
    $instances_json = json_decode(file_get_contents("instances.json"), true);

    $librey_instances = array_filter($instances_json['instances'], fn($n) => $n['librey']);
    $librex_instances = array_filter($instances_json['instances'], fn($n) => !$n['librey']);


    function list_instances($instances) 
    {
        echo "<table><tr>";
        echo "<th>Clearnet</th>";
        echo "<th>Tor</th>";
        echo "<th>I2P</th>";
        echo "<th>Country</th>";
        echo "</tr>";

        foreach($instances as $instance) {
            $hostname = parse_url($instance["clearnet"])["host"];
            $country = get_country_emote($instance["country"]) . $instance["country"];

            $is_tor = !is_null($instance["tor"]);
            $is_i2p = !is_null($instance["i2p"]);

            echo "<tr>";
            echo "<td><a href=\"" . $instance["clearnet"] . "\">" . $hostname . "</a></td>";

            echo $is_tor
                ? "<td><a href=\"" . $instance["tor"] . "\">\u{2705}</a></td>"
                : "<td>\u{274C}</td>";

            echo $is_i2p
                ? "<td><a href=\"" . $instance["i2p"] . "\">\u{2705}</a></td>"
                :"<td>\u{274C}</td>";

            echo "<td>$country</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
?>
    <title>LibreY - instances</title>
    </head>
    <body>
        <div class="misc-container">
        <center>
            <h2>Libre<span class="Y">Y</span> instances</h2>
            <?php
                list_instances($librey_instances);
            ?>

                <p><?php printftext("instances_librex", "<a href=\"https://github.com/hnhx/librex\">LibreX</a>")?>:</p>
            <?php
                list_instances($librex_instances);
            ?>
        </center>
        </div>
    

<?php require "misc/footer.php"; ?>
