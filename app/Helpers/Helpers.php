<?php

if (!function_exists('pr')) {
    /**
     * Return a greeting message.
     *
     * @param string $name
     * @return string
     */
    function pr($arr){
        echo '<pre>';print_r($arr);
        echo '</pre>';
    }
}