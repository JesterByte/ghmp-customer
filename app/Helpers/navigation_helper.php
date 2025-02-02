<?php

if (!function_exists('echo_if_route')) {
    function echo_if_route($pageTitle, $currentTitle, $string) {
        // Check if the page title matches the current title
        if ($pageTitle === $currentTitle) {
            echo $string;
        }
    }
}