<?php include "config.php" ?>
    <!DOCTYPE html>
    <html>
<?php
include "head.php" ;
include_once "backend/connect.php";
session_start();
?>

<body>
<div id="pageContainer">
<?php include 'header.php'; ?>
    <main>
<?php
if (!isset($_SESSION)) {
    session_start();
}
//This will be used both to show ALL posts, and to show an induvidual posts!
//You will get the user id by a form input in the url. something like www.thewall.com/post?postID=2
//This will then be used so get the correct content.
if (isset($_GET['POST'])){

    $userID = $_GET['POST'];

    //here is where the posts page will be created if there is a $_GET post AND if it matches any post in the DB!
    //We need to check if they exist and return an error if they don't!

}
else{
    $contentArray = array();
    $count = 0;
    $query = $conn->prepare("SELECT * FROM `Content`");
    $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
    $query->store_result();
    $query->bind_result($id,$contentType,$publisher,$name,$url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags);
//    trying to create a associative array with all the content. This is how im used to working.


    while ($query->fetch()) {
        $count++;
        $contentArray[$count] = array('ID'=>$id, 'type'=>$contentType,'publisherID'=>$publisher,'name'=>$name,'url'=>$url,'image'=>$image,'webbsite'=>$webbsite,'text'=>$text,'nsfw'=>$nsfw,'publicDomain'=>$publicDomain,'rating'=>$rating,'date'=>$date,'views'=>$views,'description'=>$description,'tags'=>$tags);
//        print"<img class=\'picture\' src=\'data:image/jpeg;base64,".$image."\'/>";
    }                                                                                                               //'image'=>$image,'webbsite'=>$webbsite,'text'=>$text,
//    print"<img class=\'picture\' src=\'data:image/jpeg;base64,".$contentArray[3]['image']."\'/>";

    foreach ($contentArray as $content){
        $image = base64_encode(stripslashes($content['image']));
        print"<img class='picture' src='data:image/jpeg;base64,".$image."'/>";
    }
    //Here is where we will display ALL the posts! We could use $_POST here to make you able to search among the users.

};
?>
</main>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
/**
 * Created by PhpStorm.
 * User: Linus
 * Date: 2017-11-08
 * Time: 15:41
 */