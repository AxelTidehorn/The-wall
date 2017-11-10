<?php include "config.php" ?>
<!DOCTYPE html>
<html>
<?php
include "head.php";
include_once "backend/connect.php";

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
    if (isset($_GET['post'])) {

        $userID = $_GET['post'];
        $query = $conn->prepare("SELECT * FROM `Content` WHERE `ID` = " . $userID . "");
        $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
        $query->store_result();
        $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags);


        //    trying to create a associative array with all the content. This is how im used to working.
        while ($query->fetch()) {
            $contentArray[] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags);
        }

        //Checking if there is an entry in the DB with that ID and that the query didn't return empty
        if (isset($contentArray)) {

            $image = base64_encode(stripslashes($contentArray[0]['image']));
            print"

            <div class='contentcont'>
                <a href='content.php'><img src='data:image/jpeg;base64," . $image . "' alt='an excellent picture'></a>
                <div class='actioncont'>
                    <div class='profilecont'>
                        <a href='#' class='profilethumb'><img src='data:image/jpeg;base64," . $image . "' alt='profilethumb'></a>
                        <a class='profilename' href='LINK-TO-PROFILE'>Chef Excellence</a>
                    </div>
                    <div class='buttoncont'>
                        <a class='likebtn' href='#'>LIKE</a>
                    </div>
                </div>
            </div>
    ";
            //If the query was empty:
        } else {
            print'There were no content matching the URL. It might have been moved or Deleted.';
        }

    } else {
        $contentArray = array();
        $count = 0;
        $query = $conn->prepare("SELECT * FROM `Content`");
        $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
        $query->store_result();
        $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags);


        //    trying to create a associative array with all the content. This is how im used to working.
        while ($query->fetch()) {
            $count++;
            $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags);
        }


        foreach ($contentArray as $content) {
            $image = base64_encode(stripslashes($content['image']));
            $id = $content['ID'];
            print"
                <form action='' method='get' class='form' style='outline: solid red 1px; margin-top:15px;'>
                    <input type='hidden' value='" . $id . "' name='post'/>
                        <div class='inpost' onclick='this.parentNode.submit();'>
                            <img class='linkImg' src='data:image/jpeg;base64," . $image . "' style='height:150px; width:150px;'/>
                        </div>
                    </form>

            ";


        }
        //Here is where we will display ALL the posts! We could use $_POST here to make you able to search among the users.

    };

        ?>
    </main>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
