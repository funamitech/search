<?php
    function load_cooldowns() {
        return apcu_exists("cooldowns") ? apcu_fetch("cooldowns") : array();
    }

    function save_cooldowns($cooldowns) {
        apcu_store("cooldowns", $cooldowns);
    }

    function set_cooldown($instance, $timeout, $cooldowns) {
        $cooldowns[$instance] = time() + $timeout;
        save_cooldowns($cooldowns);
        return $cooldowns;
    }

    function has_cooldown($instance, $cooldowns) {
        return ($cooldowns[$instance] ?? 0) > time();
    }
?>
