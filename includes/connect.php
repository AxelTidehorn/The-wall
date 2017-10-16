<?php

//Konstanter för anslutningen
define("DB_SERVER", "berzanappen.se");
define("DB_USER", "berzanap_linus");
define("DB_PASSWORD", "ab92orre1");
define("DB_NAME", "berzanap_linus");

// Användning av konstanter

$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_SERVER . ';charset=utf8';

//Inställningar

$attributes = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$dbh = new PDO($dsn, DB_USER, DB_PASSWORD, $attributes);

//Lyckades?

if (empty($dbh)){
    throw Exception("PDO FEL!!!!!");
}else{

    //print '<p>Lyckad anslutning!</p>';
}