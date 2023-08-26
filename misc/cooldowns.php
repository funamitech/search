<?php
    function load_cooldowns() {
        if (function_exists("apcu_fetch"))
            return apcu_exists("cooldowns") ? apcu_fetch("cooldowns") : array();
        return array();
    }

    function save_cooldowns($cooldowns) {
        if (function_exists("apcu_store"))
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
