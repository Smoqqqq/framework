<?php

/**
 * dumps and die
 * 
 * @param mixed $value      The thing to dump
 */
function dd($value){
    var_dump($value);
    die;
}

/**
 * dumps
 * 
 * @param mixed $value      The thing to dump
 */
function d($value){
    var_dump($value);
}