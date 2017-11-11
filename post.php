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

                            $image = base64_encode(stripslashes($contentArray[0]['image']));
                            print"
                                <div class='contentcont'>
                                    <a href='index.php#" . $contentArray[0]["ID"] . "'><img src='data:image/jpeg;base64," . $image . "' alt='an excellent picture'></a>
                                    <div class='backButton'><a href='index.php#" . $contentArray[0]["ID"] . "'>Back</a></div> <!-- Realized we might not need this here, but could possibly use it elsewhere if needed. -->
                                    <div class='actioncont contentInfo'>
                                        <div class='contentName'>" . $contentArray[0]["name"] . "</div>

                                        <div class='contentBox'>
                                            <div class='profilecont'>
                                                <a href='#' class='profilethumb'><img src='imgs/axel.jpg' alt='profilethumb'></a>
                                                <a class='profilename' href='LINK-TO-PROFILE'>" . $publisherName . "</a>
                                            </div>
                                            <div class='buttoncont'>
                                                <a class='likebtn' href='#'>LIKE (" . $contentArray[0]["rating"] . ")</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";

                            echo '
                            <section>
                                <h2>Description</h2>
                                <p>' . $contentArray[0]["description"] . '</p>
                            </section>
                            <section class="comments">
                                <form method="POST">
                                    <label>Make a commment</label>
                                    <textarea class="commentBox" name="comment">';
                                            include("backend/connect.php");

                                            @ session_start();

                                            //If you are trying to post a comment and if you are logged in, post a comment to the database basically. (unfinished)
                                            if (isset($_SESSION["username"]) && isset($_POST["comment"]) && !empty($_POST["comment"])) {
                                                $username = $_SESSION["username"];
                                                $query = $conn->prepare("INSERT INTO Comments (publisher, content, date, comment) VALUES(?, ?, ?, ?)"); //Lack of content foreign key might be the cause of it not working currently.
                                                $date = date("Y-m-d");
                                                $query->bind_param("ssss", $_SESSION["user_id"], $contentArray[0]["ID"], $date, $_POST["comment"]);
                                                $query->execute();
                                            } else if (!isset($_SESSION["username"])) {
                                                echo 'Log in to comment on content.';
                                            }
                                     echo '</textarea>
                                    <input type="submit" value="Publish comment"></input>
                                </form>
                                <h2>Comments</h2>';
                                 //Loop through the comments and add them (might not work yet either)
                                    $query = $conn->prepare("SELECT publisher, date, comment FROM Comments WHERE content = '" . $contentArray[0]["ID"] . "'");
                                    $query->bind_result($publisher, $date, $comment);
                                    $query->execute();
                                    //$query->close();

                                    $commentsArray = array();

                                    while ($query->fetch()) {
                                        $commentsArray[] = array("publisher" => $publisher, "date" => $date, "comment" => $comment);
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
                                                <p>' . $comment["comment"] . '</p>
                                                <span>' . $comment["date"] . '</span>
                                                <a class="likebtn">Like</a>
                                            </div>
                                        ';

                                        $query->close();
                                    }

                                echo '
                                <div class="comment">
                                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                    <a href="#" class="profilename">Axel</a>
                                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                                    <p>2017-05-11</p>
                                    <a href="#" class="likebtn">Like</a>
                                </div>
                                <div class="comment">
                                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                    <a href="#" class="profilename">Axel</a>
                                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                                    <p>2017-05-11</p>
                                    <a href="#" class="likebtn">Like</a>
                                </div>
                                <div class="comment">
                                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                    <a href="#" class="profilename">Axel</a>
                                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                                    <p>2017-05-11</p>
                                    <a href="#" class="likebtn">Like</a>
                                </div>
                            </section>
                            ';

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
                                    <div onclick='this.parentNode.submit();'>
                                        <img class='linkImg' src='data:image/jpeg;base64," . $image . "'/>
                                    </div>
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

              </div>
        </div>
    </body>
</html>
