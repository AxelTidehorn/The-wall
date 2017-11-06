<?php
    session_start();

    if (!isset($_SESSION["username"])) {
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
                <div class="subHeader">
                  <div class="subHeadItem"><a href="userProfile.php">Profile</a></div>
                  <div class="subHeadItem"><a href="friends.php">Friends</a></div>
                  <div class="subHeadItem"><a href="liked.php">Liked</a></div>
                  <div class="subHeadItem"><a href="logout.php">Log Out</a></div>
                </div>
                <div class="profPicCont">
                  <img src="imgs/axel.jpg" alt="profilepic">
                </div>

                <section> <!--class="profileDescription"-->
                    <?php //Connect to DB, fetch saved username value from session cookie, fetch information through SQL query and display information.
                        include("backend/connect.php");
                        $username = $_SESSION["username"];

                        if (isset($_POST["description"]) && !empty($_POST["description"])) { //If they sent a "post request" by the description area and it's not empty, change the description to the input.
                            $query = $conn->prepare("UPDATE Users SET description = ? WHERE username = '{$username}'");
                            $query->bind_param("s", $_POST["description"]);
                            $query->execute();
                        }

                        $query = $conn->prepare("SELECT joinDate, description FROM Users WHERE username = '{$username}'");
                        $query->bind_result($joinDate, $description);
                        $query->execute();

                        while ($query->fetch()) {
                            echo '
                                <h1>' . $username . '</h1>
                                <span>Joined ' . $joinDate . '</span>
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
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
