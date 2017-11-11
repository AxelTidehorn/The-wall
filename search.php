<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php include "head.php" ?>

    <body>
        <div id="pageContainer">
            <?php include "testHead.php" ?>
          <main>
            <section>
                <form action="">
                    <input type="text" name="advancedSearch">
                    What type of content do you wish to search for?
                    Images
                    <input type="checkbox" name="imageSearch">
                    Webbsites
                    <input type="checkbox" name="WebbsiteSearch">
                    Text
                    <input type="checkbox" name="TextSearch">
                    Users
                    <input type="checkbox" name="UserSearch">
                    <input type="submit">
                </form>
                <?php //Some kind of search functionality based on the comment structure currently.
                    include("backend/connect.php");

                    @ session_start();

                    if (isset($_SESSION["username"])) {
                        $sessionUser = $_SESSION["username"];
                    } else {
                        $sessionUser = false;

                    }
                        include "config.php";
                        include("backend/connect.php");

                        if ($sessionUser) {
                            $query = $conn->prepare("SELECT friends FROM Users WHERE username = '{$sessionUser}'"); //Fetching the logged in users friend list
                            $query->bind_result($friends);
                            $query->execute();
                            $query->fetch();
                        }

                        if (isset($_GET["addContact"])) { //Adds a contact to the logged in user's friend list.
                            $contact = $_GET["addContact"];

                            if ($friends) {
                                $friendArray = explode("/", $friends); //Splits up the friends separated by slashes into an array and looks through it to see if they are already friends.
                                $alreadyFriends = false;
                                foreach ($friendArray as $friend) {
                                    if ($friend === $contact) {
                                        $alreadyFriends = true;
                                    }

                                }

                                if (!$alreadyFriends) { //Adds a slash and the friend name if they already have friends and if they aren't friends.
                                    $friends = $friends . "/" . $contact;
                                }
                            } else {
                                $friends = $contact;
                            }

                            include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
                            $query = $conn->prepare("UPDATE Users SET friends = ? WHERE username = '{$sessionUser}'"); //Save/update the friend list.
                            $query->bind_param("s", $friends);
                            $query->execute();
                        }

                        if (isset($_GET["removeContact"])) { //Remove a contact from the friend list.
                            $contact = $_GET["removeContact"];

                            if ($friends) {
                                $friendArray = explode("/", $friends);
                                $id = array_search($contact, $friendArray); //Finds the "id" of the location of the friend that is being removed in the split up friends array.
                                unset($friendArray[$id]); //Removes that part of the array.

                                $newFriendArray = array();

                                foreach ($friendArray as $friend) { //Uses the old array parts to create a new array with slashes inbetween names.
                                    $newFriendArray[] = $friend . "/";
                                }

                                $friends = implode($newFriendArray); //Makes it into a string again.
                                $friends = substr($friends, 0, strlen($friends) - 1); //Starts the string and takes all of it except the last character to skip the last "/"

                                include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
                                $query = $conn->prepare("UPDATE Users SET friends = ? WHERE username = '{$sessionUser}'"); //Update the friend list.
                                $query->bind_param("s", $friends);
                                $query->execute();
                            }
                        }

                        //Seeing if this is a general search from the searchfield, or a more andvanced search! (will be added later)
                        if (isset($_GET['generalSearch'])) {
                            print'<h2>User results</h2>';
                            include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
                            $search = $_GET["generalSearch"]; //Gets the search term and retrieves matches similar to the search term. (may want to add more things than users in the future)
                            $search = mysqli_real_escape_string($conn, $search); //Not using htmlentities here as we are just searching and retrieving content here, not adding html.
                            $query = $conn->prepare("SELECT username, id FROM Users WHERE username LIKE '%" . $search . "%'");
                            $query->bind_result($username, $userID);
                            $query->execute();

                            while ($query->fetch()) {
                                if ($sessionUser !== false && $username != $sessionUser) { //Basically if the session user exists (that someone is logged in) and if the user we are looking at is not the user that is currently logged in. (why would he want to add himself?)
                                    $link = explode("&", $currentURI); //Prepares the link that will be used on the add/remove contact buttons and preventing the URI from ending up in an endless sequence of GETs in the link.

                                    if ($link === array($currentURI)) { //Explode makes it into an array, so compare with the URI as array, handle the link differently if there is no addContact or whatever GET in the link already.
                                        $noChange = true;
                                    } else {
                                        $noChange = false;
                                    }

                                    if ($friends) {
                                        $friendArray = explode("/", $friends);
                                        $alreadyFriends = false;
                                        foreach ($friendArray as $friend) {
                                            if ($friend === $username) {
                                                $alreadyFriends = true;
                                            }
                                        }

                                        if ($alreadyFriends) { //Display it as remove contact if they are already friends and vice versa.
                                            $end = "&removeContact=" . $username;
                                            $contactString = "Remove contact";
                                            $class = "unlikebtn";
                                        } else {
                                            $end = "&addContact=" . $username;
                                            $contactString = "Add contact";
                                            $class = "likebtn";
                                        }
                                    } else { //Kind of comes in if the current user ends up having no friends
                                        $end = "&addContact=" . $username;
                                        $contactString = "Add contact";
                                        $class = "likebtn";
                                    }

                                }

                                //Seeing if this is a general search from the searchfield, or a more andvanced search! (will be added later)
                                if (isset($_GET['generalSearch'])) {
                                    include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
                                    $search = $_GET["generalSearch"]; //Gets the search term and retrieves matches similar to the search term. (may want to add more things than users in the future)
                                    $search = mysqli_real_escape_string($conn, $search); //Not using htmlentities here as we are just searching and retrieving content here, not adding html.
                                    $query = $conn->prepare("SELECT username, id FROM Users WHERE username LIKE '%" . $search . "%' LIMIT 5");
                                    $query->bind_result($username, $userID);
                                    $query->execute();
                                    $query->store_result();


                                    while ($query->fetch()) {
                                        if ($sessionUser !== false && $username != $sessionUser) { //Basically if the session user exists (that someone is logged in) and if the user we are looking at is not the user that is currently logged in. (why would he want to add himself?)
                                            $link = explode("&", $currentURI); //Prepares the link that will be used on the add/remove contact buttons and preventing the URI from ending up in an endless sequence of GETs in the link.

                                            if ($link === array($currentURI)) { //Explode makes it into an array, so compare with the URI as array, handle the link differently if there is no addContact or whatever GET in the link already.
                                                $noChange = true;
                                            } else {
                                                $noChange = false;
                                            }

                                            if ($friends) {
                                                $friendArray = explode("/", $friends);
                                                $alreadyFriends = false;
                                                foreach ($friendArray as $friend) {
                                                    if ($friend === $username) {
                                                        $alreadyFriends = true;
                                                    }
                                                }

                                                if ($alreadyFriends) { //Display it as remove contact if they are already friends and vice versa.
                                                    $end = "&removeContact=" . $username;
                                                    $contactString = "Remove contact";
                                                    $class = "unlikebtn";
                                                } else {
                                                    $end = "&addContact=" . $username;
                                                    $contactString = "Add contact";
                                                    $class = "likebtn";
                                                }
                                            } else { //Kind of comes in if the current user ends up having no friends
                                                $end = "&addContact=" . $username;
                                                $contactString = "Add contact";
                                                $class = "likebtn";
                                            }

                                            if ($noChange) { //Basically in this context, if there is no addContact or removeContact GET in the URI, add it, otherwise replace the old one.
                                                $link[] = $end;
                                            } else {
                                                $link[sizeof($link) - 1] = $end; //Replaces the last part of the array.
                                            }

                                            $link = implode($link); //Make it a string again
                                        }

                                        //Actually adding the friends and buttons, etc. to the page.
                                        echo '
                            <a href="user.php?user_ID=' . $userID . '">
                            <div class="comment">
                                <a href="user.php?user_ID=' . $userID . '" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                <a href="user.php?user_ID=' . $userID . '" class="profilename">' . $username . '</a>';

                                        if ($sessionUser !== false && $username != $sessionUser) {
                                            echo '<a href="' . $link . '" class="' . $class . '">' . $contactString . '</a>';
                                        }
                                        echo '</div></a>';
                                    }


                                    print'<div id="searchPage">';
                                    $count = 0;
                                    $query = $conn->prepare("SELECT * FROM `Content` LIMIT 5");
                                    $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                                    $query->store_result();
                                    $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoise);




                                    //    trying to create a associative array with all the content. This is how im used to working.
                                    while ($query->fetch()) {
                                        $count++;
                                        $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags);
                                    }
                                    if($contentArray != null){
                                        print'<h2>Post results</h2>';
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
                                        <a class='profilename' href='LINK-TO-PROFILE'>Chef Excellence</a>
                                    </div>
                                    <div class='buttoncont'>
                                        <a class='likebtn' href='#'>LIKE</a>
                                    </div>
                                </div>
                            </form>
                        ";
                                    }
                                    echo "</div>";
                                }
                            }

                    }
            ?>
        </section>
    </main>
</div>
</body>
</html>
