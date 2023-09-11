<?php


if (! function_exists('booleanSelect')) {
    function booleanSelect($value):string
    {
        return $value==1?'yes':'no';
    }
}
