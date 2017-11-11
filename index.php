<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php
        include "head.php" ;
        //include_once "backend/connect.php";
    ?>

    <body>
        <div id="pageContainer">
            <?php include 'testHead.php'; ?>

            <main>
                <?php
                if (!isset($_COOKIE["welcomed"])) { //Display the welcome message if the user has not been welcomed, and include a script to determine if it has been clicked.
                    echo '
                            <section id="welcome">
                                <p>Welcome to The Wall. Here you can share your creations and view the creations of others. By using this site you agree to our usage of cookies.</p>
                                <div class="hide">
                                    <a href="#">Hide</a>
                                </div>
                            </section>

                            <script src="js/hide.js"></script>
                        ';
                }

                function loadContent($type) {
                    include "backend/connect.php";

                    //if (!isset($_SESSION)) {
                        @ session_start();
                    //}

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
                                        <a class='profilename' href='LINK-TO-PROFILE'>Might not be used actually in index</a>
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
                        $query = $conn->prepare("SELECT likedContent FROM Users WHERE id = " . $_SESSION["user_id"]);
                        $query->bind_result($likedContent);
                        $query->execute();
                        $query->fetch();
                        $where = "";

                        if ($likedContent) {
                            $likedArray = explode("/", $likedContent);
                            $where = " WHERE id = " . reset($likedArray);

                            for ($i = 1; $i < sizeof($likedArray); $i++) {
                                $where = $where . " OR id = " . $likedArray[$i];
                            }
                        }

                        $query->close();

                        if ($likedContent) {
                            $likedArray = explode("/", $likedContent);
                            $where = " WHERE id = " . reset($likedArray);

                            for ($i = 1; $i < sizeof($likedArray); $i++) {
                                $where = $where . " OR id = " . $likedArray[$i];
                            }
                        }

                        //$contentArray = array();
                        $count = 0;

                        if ($type == "Latest") {
                            echo '<div id="newest">';
                        } else if ($type == "Top Rated") {
                            echo '<div id="topRated">';
                        } else if ($type == "Editor's Choice") {
                            echo '<div id="editorsChoice">';
                        }

                        echo '
                            <div class="center-text">
                                <h2><a href="#">' . $type . ' Content</a></h2>
                            </div>
                        ';

                            if ($type == "Editor's Choice") {
                                $query = $conn->prepare("SELECT * FROM `Content` WHERE editorsChoice = true");
                            } else {
                                $query = $conn->prepare("SELECT * FROM `Content`");
                            }
                            $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                            $query->store_result();
                            $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoice);


                            //    trying to create a associative array with all the content. This is how im used to working.
                            while ($query->fetch()) {
                                $count++;
                                $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags, 'editorsChoice' => $editorsChoice);
                            }

                            $query->close();

                            if ($type == "Latest") $contentArray = array_reverse($contentArray, true); //true to keep the keys of the array, seems to work without it though.
                            //Simply reversing the array assuming they are added in chronological order to get the latest instead of making lots of sql queries to check different time frames.

                            foreach ($contentArray as $content) {
                                $query = $conn->prepare("SELECT username FROM Users WHERE id = '" . $content['publisherID'] . "'");
                                $query->bind_result($publisherName);
                                $query->execute();
                                $query->fetch();

                                $image = base64_encode(stripslashes($content['image']));
                                $webbsite = base64_encode(stripslashes($content['webbsite']));
                                $id = $content['ID'];

                                //Likes
                                include("backend/connect.php");

                                //@ session_start();

                                if (isset($_SESSION["username"])) {
                                    $sessionUser = $_SESSION["username"];
                                } else {
                                    $sessionUser = false;
                                }

                                //include "config.php";
                                //include("backend/connect.php");

                                if ($sessionUser) {
                                    $query = $conn->prepare("SELECT likedContent FROM Users WHERE username = '{$sessionUser}'");
                                    $query->bind_result($liked);
                                    $query->execute();
                                    $query->fetch();
                                }

                                include("likeHandler.php");


                                print"
                                    <form action='post.php' method='get' class='form contentcont' id='" . $content["ID"] . "'>
                                        <input type='hidden' value='" . $id . "' name='post'/>
                                        <div onclick='this.parentNode.submit();'>";
                                            if ($content["type"] == "text") {
                                                echo "<img class='linkImg' src='imgs/text.png'/>";
                                            } else if ($content["type"] == "website") {
                                                echo "<img class='linkImg' src='data:image/jpeg;base64," . $webbsite . "'/>";
                                            } else {
                                                echo "<img class='linkImg' src='data:image/jpeg;base64," . $image . "'/>";
                                            }
                                        echo "</div>
                                        <div class='actioncont'>
                                            <div class='contentName'>" . $content["name"] . "</div>

                                            <div class='contentBox'>
                                                <div class='profilecont'>
                                                    <a href='#' class='profilethumb'><img src='imgs/axel.jpg' alt='profilethumb'></a>
                                                    <a class='profilename' href='LINK-TO-PROFILE'>" . $publisherName . "</a>
                                                </div>
                                                <div class='buttoncont'>
                                                    <a class='" . $class . "' href='" . $link . "'>" . $likeString . " (" . $content["rating"] . ")</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                ";

                                //$query->close(); //This seems to fix it, not sure why. Maybe it doesn't like leftover stored information or something?

                            }
                            echo '</div>';
                        }
                        //Here is where we will display ALL the posts! We could use $_POST here to make you able to search among the users.

                    //};
                    }

                    $GLOBALS["updatedRating"] = false; //Using a global variable to make it apply to the functions above correctly.
                    loadContent("Latest");
                    loadContent("Top Rated"); //This does not work currently, can be good if you can actually rate content first
                    loadContent("Editor's Choice");

                ?>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
