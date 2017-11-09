<?php
$servername = "berzanappen.se";
$username = "berzanap_linus";
$password = "ab92orre1";
$database = "berzanap_linus";

try {
    /*$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);*/

    //Currently using mysqli connection instead, did not quite work before, may be changed back later
    $conn = new mysqli($servername, $username, $password, $database);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
?>
