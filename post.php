<?php include "config.php" ?>
<!DOCTYPE html>
<html>
    <?php
        include "head.php";
        include_once "backend/connect.php";
    ?>

    <body>
        <div id="pageContainer" class="contentPage">
                <?php
                    include("testHead.php");

                    echo '<main>';

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
                            $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoice);


                            //    trying to create a associative array with all the content. This is how im used to working.
                            while ($query->fetch()) {
                                $contentArray[] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags, 'editorsChoice' => $editorsChoice);
                            }

                            //Checking if there is an entry in the DB with that ID and that the query didn't return empty
                            if (isset($contentArray)) {
                                $query = $conn->prepare("SELECT username FROM Users WHERE id = '" . $contentArray[0]['publisherID'] . "'");
                                $query->bind_result($publisherName);
                                $query->execute();
                                $query->fetch();

                                $image = base64_encode(stripslashes($contentArray[0]['image'])); //<img src='data:image/jpeg;base64," . $image . "' alt='an excellent picture'>
                                print"
                                <div>
                                    <div class='contentcont'>
                                        <a href='index.php#" . $contentArray[0]["ID"] . "'>";
                                        if ($contentArray[0]["type"] == "text") {
                                            echo "<img class='thepost' src='imgs/text.png'/>";
                                        } else if ($contentArray[0]["type"] == "website") {
                                            echo "<img class='thepost' src='data:image/jpeg;base64," . $webbsite . "'/>";
                                        } else {
                                            echo "<img class='thepost' src='data:image/jpeg;base64," . $image . "'/>";
                                        }
                                        echo "</a>
                                        <div class='backButton'><a href='index.php#" . $contentArray[0]["ID"] . "'>Back</a></div> <!-- Realized we might not need this here, but could possibly use it elsewhere if needed. -->
                                        <div class='actioncont contentInfo'>
                                            <div class='contentName'>" . $contentArray[0]["name"] . "</div>

                                            <div class='contentBox'>
                                                <div class='profilecont'>
                                                    <a href='#' class='profilethumb'><img src='imgs/axel.jpg' alt='profilethumb'></a>
                                                    <a class='profilename' href='user.php?user_ID=" . $contentArray[0]["ID"] . "'>" . $publisherName . "</a>
                                                </div>
                                                <div class='buttoncont'>
                                                    <a class='likebtn' href='#'>LIKE (" . $contentArray[0]["rating"] . ")</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ";

                                echo '
                                <section class="description">
                                    <h2 id="getdown">Description</h2>
                                    <p>' . $contentArray[0]["description"] . '</p>
                                </section>';
                                if ($contentArray[0]["type"] == "text") {
                                    echo '
                                        <section>
                                            <h2>Text content</h2>
                                            <p>' . $contentArray[0]["text"] . '</p>
                                        </section>
                                    <div>
                                    ';
                                }
                                echo '<section class="comments">
                                    <form method="POST">
                                        <label>Make a commment</label>
                                        <textarea class="commentBox" name="comment">';
                                                include("backend/connect.php");

                                                @ session_start();

                                                //If you are trying to post a comment and if you are logged in, post a comment to the database basically. (unfinished)
                                                if (isset($_SESSION["username"]) && isset($_POST["comment"]) && !empty($_POST["comment"] && !isset($_POST["save"]))) {
                                                    $query = $conn->prepare("SELECT comment FROM Comments WHERE content = '" . $contentArray[0]["ID"] . "' AND publisher = '" . $_SESSION["user_id"] . "' AND comment = '" . $_POST["comment"] . "'");
                                                    $query->execute();
                                                    $query->store_result();

                                                    if (!$query->num_rows()) { //Basically a test so when the user refreshes the page and resends forms, it doesn't send the same message over and over. It kind of takes care of spam as well so you can't send the same exact message multiple times.
                                                        $username = $_SESSION["username"];
                                                        $query = $conn->prepare("INSERT INTO Comments (publisher, content, date, comment) VALUES(?, ?, ?, ?)"); //Lack of content foreign key might be the cause of it not working currently.
                                                        $date = date("Y-m-d");
                                                        $query->bind_param("ssss", $_SESSION["user_id"], $contentArray[0]["ID"], $date, $_POST["comment"]);
                                                        $query->execute();
                                                    }
                                                } else if (!isset($_SESSION["username"])) {
                                                    echo 'Log in to comment on content.';
                                                }
                                         echo '</textarea>
                                            <input type="submit" value="Publish comment"></input>
                                        </form>
                                        <h2>Comments</h2>';

                                        if (isset($_POST["save"])){
                                            $query = $conn->prepare("UPDATE Comments SET comment = ? WHERE id = '" . $_POST["commentId"] . "'");
                                            $comment = $_POST["comment"];
                                            $query->bind_param("s", $comment);
                                            $query->execute();
                                            $query->close();
                                        } else if (isset($_POST["remove"])){
                                            $query = $conn->prepare("DELETE FROM Comments WHERE id = ?");
                                            $commentId = $_POST["commentId"];
                                            $query->bind_param("i", $commentId);
                                            $query->execute();
                                            $query->close();
                                        }

                                     //Loop through the comments and add them (might not work yet either)
                                        $query = $conn->prepare("SELECT id, publisher, date, comment FROM Comments WHERE content = '" . $contentArray[0]["ID"] . "'");
                                        $query->bind_result($id, $publisher, $date, $comment);
                                        $query->execute();
                                        //$query->close();

                                        $commentsArray = array();

                                        while ($query->fetch()) {
                                            $commentsArray[] = array("id" => $id, "publisher" => $publisher, "date" => $date, "comment" => $comment);
                                        }

                                        $query->close();

                                        foreach($commentsArray as $comment) {
                                            $query = $conn->prepare("SELECT username FROM Users WHERE ID = '" . $comment["publisher"] . "'");
                                            $query->execute();
                                            $query->bind_result($publisherName);
                                            $query->fetch();

                                            echo '
                                                <div class="comment">
                                                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                                    <a href="#" class="profilename">' . $publisherName . '</a>
                                                    <form method="POST">';
                                                        echo '<input type="hidden" name="commentId" value = "' . $comment["id"] . '" class="actionLink" />';

                                                        if (isset($_POST["edit-" . $comment["id"]])){
                                                            echo '<textarea name="comment">' . $comment["comment"] . '</textarea>';
                                                            echo '<input type="submit" name="save" value="Save changes" class="actionLink" />';
                                                        } else {
                                                            echo '<p>' . $comment["comment"] . '</p>';
                                                        }
                                                        echo '<span class="insignificant">' . $comment["date"] . '</span>';

                                                        if ($comment["publisher"] == $_SESSION["user_id"]) {
                                                            echo '<input type="submit" name="edit-' . $comment["id"] . '" value="Edit" class="actionLink" />
                                                                <input type="submit" name="remove" value="Remove" class="actionLink" />';
                                                        }
                                                    echo '</form>
                                                    <a class="likebtn">Like</a>
                                                </div>
                                            ';

                                            $query->close();
                                        }

                                //$query->close();
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
                                    <form action='post.php' method='get' class='form contentcont'>
                                        <input type='hidden' value='" . $id . "' name='post'/>
                                        <div onclick='this.parentNode.submit();'>";
                                            if ($content["type"] == "text") {
                                                echo "<img class='thepost' src='imgs/text.png'/>";
                                            } else {
                                                echo "<img class='thepost' src='data:image/jpeg;base64," . $image . "'/>";
                                            }
                                        "</div>
                                        <div class='actioncont'>
                                            <div class='profilecont'>
                                                <a href='#' class='profilethumb'><img src='imgs/axel.jpg' alt='profilethumb'></a>
                                                <a class='profilename' href='LINK-TO-PROFILE'>" . $publisherName . "</a>
                                            </div>
                                            <div class='buttoncont'>
                                                <a class='likebtn' href='#'>LIKE</a>
                                            </div>
                                        </div>
                                    </form>
                                ";


                            }
                            //Here is where we will display ALL the posts! We could use $_POST here to make you able to search among the users.

                        };

                    ?>
                    </main>
              </div>
        </div>
    </body>
</html>
