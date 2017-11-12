<h2>Friends</h2>
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
    } else if (isset($_GET["removeContact"])) { //Remove a contact from the friend list.
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

    if ($friends) {
        $friendArray = explode("/", $friends);

        foreach ($friendArray as $friend) {

            //Actually adding the friends and buttons, etc. to the page.
            echo '
                <div class="comment">
                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                    <a href="#" class="profilename">' . $friend . '</a>
                    <a href="friends.php?removeContact=' . $friend .'" class="unlikebtn">Remove friend</a>
                </div>
            ';
        }
    }
?>
