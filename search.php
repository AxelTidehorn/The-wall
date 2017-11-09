<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php include "head.php" ?>

    <body>
        <div id="pageContainer" class="contentPage">
            <?php include "header.php" ?>
          <div class="contentcont">
            <section class="comments">
                <h2>Comments</h2>
                <?php //Some kind of search functionality based on the comment structure currently.
                    include("backend/connect.php");

                    session_start();

                    $sessionUser = $_SESSION["username"];

                    $query = $conn->prepare("SELECT friends FROM Users WHERE username = '{$sessionUser}'"); //Fetching the logged in users friend list
                    $query->bind_result($friends);
                    $query->execute();
                    $query->fetch();

                    if (isset($_GET["addContact"])) { //Adds a contact to the logged in user's friend list.
                        $contact = $_GET["addContact"];

                        if ($friends) {
                            $friendArray = explode("/", $friends); //Splits up the friends separated by slashes into an array and looks through it to see if they are already friends.
                            $alreadyFriends = false;
                            foreach($friendArray as $friend) {
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

                    include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...

                    $search = $_GET["search"]; //Gets the search term and retrieves matches similar to the search term. (may want to add more things than users in the future)
                    $query = $conn->prepare("SELECT username FROM Users WHERE username LIKE '%" . $search . "%'");
                    $query->bind_result($username);
                    $query->execute();

                    while ($query->fetch()) {
                        $link = explode("&", $currentURI); //Prepares the link that will be used on the add/remove contact buttons and preventing the URI from ending up in an endless sequence of GETs in the link.

                        if ($link === array($currentURI)) { //Explode makes it into an array, so compare with the URI as array, handle the link differently if there is no addContact or whatever GET in the link already.
                            $noChange = true;
                        } else {
                            $noChange = false;
                        }

                        if ($friends) {
                            $friendArray = explode("/", $friends);
                            $alreadyFriends = false;
                            foreach($friendArray as $friend) {
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

                        //Actually adding the friends and buttons, etc. to the page.
                        echo '
                            <div class="comment">
                                <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                <a href="#" class="profilename">' . $username . '</a>
                                <a href="' . $link . '" class="' . $class . '">' . $contactString . '</a>
                            </div>
                        ';
                    }

                    $query->close();
                ?>
            </section>
          </div>
        </div>
    </body>
</html>
