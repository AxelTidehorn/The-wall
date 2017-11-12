<?php
$deletedID = $_GET['id'];
$returnAdress = $_GET['posterID'];

require_once 'connect.php';
$query = $conn->prepare("DELETE FROM `Content` WHERE `ID`='".$deletedID."'");
$query->execute();

header("location: ../user.php?user_ID=".$returnAdress);