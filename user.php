<?php include "config.php" ?>
<!DOCTYPE html>
<html>
<?php
include "head.php";
include_once "backend/connect.php";
?>

<body>
<div id="pageContainer">
    <?php include 'testHead.php'; ?>
    <main>
        <?php
        if (!isset($_SESSION)) {
            session_start();
        }

        //This will be used both to show ALL posts, and to show an induvidual posts!
        //You will get the user id by a form input in the url. something like www.thewall.com/post?user_ID=2
        //This will then be used so get the correct content.
        if (isset($_GET['user_ID'])) {

            include_once "testSubHeader.php";

            echo "<section>";

            //checking the url for "&friends". This makes you able to stay on the same page and load in the users friends.
            //Also you could implement the "admin" features here for the user if it is their own friends list!
            if (isset($_GET['friends'])) {
                include("friends.php");
            } //same as above, but for "liked"
            else if (isset($_GET['liked'])) {
                include("liked.php");
            } //If there is no _GET at all, simply list all users.
            else {

                $userID = $_GET['user_ID'];
                $query = $conn->prepare("SELECT `id`,`username`,`Friends`,`JoinDate`,`LastActive`,`Views`,`Description`,`ProfileImage` FROM `Users` WHERE `id` = $userID");
                $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                $query->store_result();
                $query->bind_result($id, $userName, $friends, $joinDate, $lastActive, $views, $description, $profileImage);


                //    trying to create a associative array with all the content. This is how im used to working.
                while ($query->fetch()) {
                    $contentArray[] = array('ID' => $id, 'userName' => $userName, 'joinDate' => $joinDate, 'lastActive' => $lastActive, 'views' => $views, 'description' => $description, 'profileImage' => $profileImage);
                }

                //Checking if there is an entry in the DB with that ID and that the query didn't return empty
                if (isset($contentArray)) {

                    //Determening if the currently viewed page is your own, and adding "admin" features if so!
                    /*if ($userID == $_SESSION['user_id']) {
                        print'This is your page';


                        $query = $conn->prepare("UPDATE Users SET friends = ? WHERE username = '.$userID.' LIMIT 5"); //Update the friend list.
                        $query->bind_param("s", $friends);
                        $query->execute();

                        if ($friends) {
                            $friendArray = explode("/", $friends);


                            foreach ($friendArray as $friend) {

                                //Actually adding the friends and buttons, etc. to the page.
                                echo '
                                    <div class="comment">
                                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                        <a href="#" class="profilename">' . $friend . '</a>
                                        <a href="friends.php?removeContact=' . $friend . '" class="unlikebtn">Remove friend</a>
                                    </div>
                                ';
                            }

                        }
                    }*/

                    $image = base64_encode(stripslashes($contentArray[0]['profileImage']));
                    $username = $contentArray[0]['userName'];
                    $joinDate = $contentArray[0]['joinDate'];
                    $description = $contentArray[0]['description'];
                    print"
                    <div class='profPicCont'>
                        <img src='imgs/axel.jpg' alt='profilepic'>
                    </div>
                    <h1 class='center-text profile'>" . $username . "</h1>
                    <span class='joined'>Joined " . $joinDate . "</span>
                    <h3 class='center-text'>Description</h3>
                    <div class='descriptionCont'>
                      <p>
                      " . $description . "
                      </p>
                    </div>
                    <h2>Profile</h2>
                    ";
                    if ($_GET['user_ID'] == $_SESSION['user_id']) {
                        unset($contentArray);
                        unset($query);
                        $query = $conn->prepare("SELECT * FROM `Content` WHERE `Publisher`='" . $_GET['user_ID'] . "'");
                        $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                        $query->store_result();
                        $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoice);
                        $count = 0;

                        //    trying to create a associative array with all the content. This is how im used to working.
                        while ($query->fetch()) {
                            $count++;
                            $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags, 'editorsChoice' => $editorsChoice);
                        }

                        $query->close();
                        //Funnily enough, it seems like we want to reverse it no matter the type. Either newest to oldest, or highest rating to lowest.
                        //$contentArray = array_reverse($contentArray, true); //true to keep the keys of the array, seems to work without it though.
                        //Simply reversing the array assuming they are added in chronological order to get the latest instead of making lots of sql queries to check different time frames.

                        foreach ($contentArray as $content) {
                            $image = base64_encode(stripslashes($content['image']));
                            $webbsite = base64_encode(stripslashes($content['webbsite']));
                            $id = $content['ID'];
                            print"<div id='profilecont'>
                                <form action='post.php' method='get' class='form' id='" . $content["ID"] . "'>
                                    <input type='hidden' value='" . $id . "' name='post'>
                                    <div onclick='this.parentNode.submit();'>
                                    <img src='data:image/jpeg;base64," . $image . "' alt='error'>
                                    </div>
                                    </form>
                                    <div id='banner'>
                                    <span>".$content['name']."$</span>
                                    <form action='backend/delete.php'>
                                    <input name ='id' type='hidden' value='".$content['ID']."'>
                                    <input type='hidden' name='posterID' value='".$_GET['user_ID']."'>
                                    <span id='delete' onclick='this.parentNode.submit();'>Delite</span>
                                    </form>
</div>
                            </div>";

                        }
                        include_once 'config.php';
                        print '<a href="'.$baseURL."&posts".'">Klick here to see all post</a>';
                        //If the query was empty:
                    } else if($_GET['user_ID'] != $_SESSION['user_id']){
                        unset($contentArray);
                        unset($query);
                        $query = $conn->prepare("SELECT * FROM `Content` WHERE `Publisher`='" . $_GET['user_ID'] . "'");
                        $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                        $query->store_result();
                        $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoice);
                        $count = 0;

                        //    trying to create a associative array with all the content. This is how im used to working.
                        while ($query->fetch()) {
                            $count++;
                            $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags, 'editorsChoice' => $editorsChoice);
                        }
                        $query->close();
                        //Funnily enough, it seems like we want to reverse it no matter the type. Either newest to oldest, or highest rating to lowest.
                        //$contentArray = array_reverse($contentArray, true); //true to keep the keys of the array, seems to work without it though.
                        //Simply reversing the array assuming they are added in chronological order to get the latest instead of making lots of sql queries to check different time frames.

                        foreach ($contentArray as $content) {
                            $image = base64_encode(stripslashes($content['image']));
                            $webbsite = base64_encode(stripslashes($content['webbsite']));
                            $id = $content['ID'];
                            print"<div id='profilecont'>
                                <form action='post.php' method='get' class='form' id='" . $content["ID"] . "'>
                                    <input type='hidden' value='" . $id . "' name='post'>
                                    <div onclick='this.parentNode.submit();'>
                                    <img src='data:image/jpeg;base64," . $image . "' alt='error'>
                                    </div>
                                    </form>
                                    <div id='banner'>
                                    <span>".$content['name']."$</span>
                                    
</div>
                            </div>";

                        }
                        include_once 'config.php';
                        print '<a href="'.$baseURL."&posts".'">Klick here to see all post</a>';
                    }
                }
            }
        } else {
            $contentArray = array();
            $count = 0;
            $query = $conn->prepare("SELECT `id`,`username`,`Friends`,`JoinDate`,`LastActive`,`Views`,`Description`,`ProfileImage` FROM `Users`");
            $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
            $query->store_result();
            $query->bind_result($id, $userName, $friends, $joinDate, $lastActive, $views, $description, $profileImage);


            //    trying to create a associative array with all the content. This is how im used to working.
            while ($query->fetch()) {
                $count++;
                $contentArray[$count] = array('user_ID' => $id, 'userName' => $userName, 'joinDate' => $joinDate, 'lastActive' => $lastActive, 'views' => $views, 'description' => $description, 'profileImage' => $profileImage);
            }
//var_dump($contentArray);

            foreach ($contentArray as $content) {
                $image = base64_encode(stripslashes($content['profileImage']));
                $id = $content['user_ID'];
                $userName = $content['userName'];
                print"
                <form action='' method='get' class='comment' style='outline: solid red 1px; margin-top:15px;'>
                    <input type='hidden' value='" . $id . "' name='user_ID'/>
                    <div class='inpost' onclick='this.parentNode.submit();'>
                    <img class='linkImg' src='data:image/jpeg;base64," . $image . "' class='profilethumb'/>
                    <span class='profilename'>" . $userName . "</span>
                    </div>
                </form>

            ";


            }
            //Here is where we will display ALL the posts! We could use $_POST here to make you able to search among the users.

        };

        echo "</section>";

        ?>
    </main>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
