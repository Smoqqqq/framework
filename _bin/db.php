<?php

$host = str_replace(" ", "", $env["DB_HOST"]);
$name = str_replace(" ", "", $env["DB_NAME"]);
$user = str_replace(" ", "", $env["DB_USER"]);
$pass = str_replace(" ", "", $env["DB_PASS"]);

try {
    $dbh = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
} catch (PDOException $e) {
    dd("A DB error occurred : " . $e->getMessage());
}