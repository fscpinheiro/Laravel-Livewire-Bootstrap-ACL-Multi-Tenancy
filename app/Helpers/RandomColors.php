<?php 

if (!function_exists('random_colors')) {
    function random_colors(){
        return sprintf('#%06X', mt_rand(0, 0XFFFFFF));
    }
}