<?php
    $currentPage = $_SERVER["REQUEST_URI"];
    $currentPage = explode("/", $currentPage);
    $currentPage = end($currentPage);
    $currentPage = explode("?", $currentPage);
    $currentPage = reset($currentPage);
?>
