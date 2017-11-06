<?php
#Gjorde databas connectionen Objekt Orienterad. Kan vara svårt att förstå till en början, men skitbra när man väl förstår!
#Kolla på första functionen "getAllContent" Så förstår ni nog hur det funkar.
#Det är bara att skapa egna functioner här så kommer dem alltid vara tillgängliga.
function connect()
{
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
    return $dbh;
}
/**
 * @return mixed
 */
function getAllContent(){

    //Först behöver vi starta connectionen till databasen.
    $dbh = connect();

    //Sen definerar vi SQL kommandot vi vill utföra. Det här är vart magin börjar.
    //Genom att använda den här metoden håller vi alla andra filer så simpla som möjligt.
    //Vill man te.x få allt ur 'conent' så gör man bara "$variabelNamn = getAllContent()"
    //så blir "$content" en array med all data!
    $sql = " SELECT * from `Content`";

    //Hanterar sql kommandot och kör det.
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

    //Skickar tillbaka datan
    return $result;

}

//DEn här funktionen använder en variabel man anger när man kör funktionen.
//Ville man te.x ha de 5 första resultaten kör man "$variabelNamn = getNumberOfContent(5);"

function getNumberOfContent($amount){
    $dbh = connect();
    $sql = " SELECT * from `Content` LIMIT ".$amount."";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}

function getContentByPublisherID($id){
    $dbh = connect();
    $sql = "SELECT * FROM `Content` WHERE `Publisher` = ".$id."";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;

}
