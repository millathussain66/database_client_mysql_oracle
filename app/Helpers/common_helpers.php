<?php 

use Illuminate\Support\Arr;


/**
 * Prints an array in a better readable format
 */
if (!function_exists('pa')) {
    function pa($array)
    {
        echo '<pre>'; var_export($array); echo '</pre>';
    }
}
/**
 * Prints an array in a better readable format
 */
if (!function_exists('pad')) {
    function pad($array)
    {
        echo '<pre>'; var_export($array); echo '</pre>'; 
        die;
    }
}

/**
 * Prints an string in ident format
 */
if (!function_exists('nice_echo')) {
    function nice_echo($str)
    {
        echo '<pre>'; echo $str; echo '</pre>';
    }
}


/**
 * Converts array into lowercase
 */
if (!function_exists('array_to_lowercase')) {
    function array_to_lowercase($array)
    {
        return collect($array)->mapWithKeys(function ($value, $key) {
            return [$key => strtolower($value)];
        })->all();
    }
}


/**
 * Converts array into uppercase
 */
if (!function_exists('array_to_uppercase')) {
    function array_to_uppercase($array)
    {
        return collect($array)->mapWithKeys(function ($value, $key) {
            return [$key => strtouppwer($value)];
        })->all();
    }
}