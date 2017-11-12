<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("location:login.php");
    }
?>

<!DOCTYPE html>
<html>
    <?php include "head.php" ?>

    <body>
        <div id="pageContainer">
          <?php include 'header.php'; ?>

            <main>
                <?php include("subHeader.php"); ?>

                <div class="profPicCont">
                  <img src="imgs/axel.jpg" alt="profilepic">
                </div>

                <section> <!--class="profileDescription"-->
                    <?php //Connect to DB, fetch saved username value from session cookie, fetch information through SQL query and display information.
                        include("backend/connect.php");
                        $username = $_SESSION["username"];

                        if (isset($_POST["description"]) && !empty($_POST["description"])) { //If they sent a "post request" by the description area and it's not empty, change the description to the input.
                            $description = $_POST["description"];
                            $description = mysqli_real_escape_string($conn, $description);
                            $description = htmlentities($description);

                            $query = $conn->prepare("UPDATE Users SET description = ? WHERE username = '{$username}'");
                            $query->bind_param("s", $description);
                            $query->execute();
                        }

                        $query = $conn->prepare("SELECT joinDate, description FROM Users WHERE username = '{$username}'");
                        $query->bind_result($joinDate, $description);
                        $query->execute();

                        while ($query->fetch()) {
                            $description = stripslashes($description); //Removes the added mysqli real escape string slashes before displaying the description so it doesn't constantly add more and more slashes every time you save.
                            echo '
                                <!-- <a href="#" class="block center-text settingsLink">Change profile picture</a> -->
                                <h1 class="center-text profile">' . $username . '</h1>
                                <span class="block center-text">Joined ' . $joinDate . '</span>
                                <h2>Profile</h2>
                                <h3>Change profile picture</h3>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="file" name="profilePicture">
                                </form>
                                <h3>Description</h3>
                                <form method="POST">
                                    <textarea name="description">' . $description . '</textarea>
                                    <input type="submit" value="Update description" />
                                </form>
                            ';
                        }

                        $query->close();
                    ?>

                    <!-- Maybe skip this <div class="infoBox">
                        <span>Name: Axel</span>
                        <span>Gender: Axel</span>
                        <span>Age: Axel</span>
                    </div> -->
                </section>

                <section> <!-- We better make this an include or something if we keep this structure. -->
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
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
