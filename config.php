<?php
    $currentURI = $_SERVER["REQUEST_URI"];
    $currentPage = explode("/", $currentURI);
    $currentPage = end($currentPage);
    $currentPage = explode("?", $currentPage);
    $currentPage = reset($currentPage);
?>
