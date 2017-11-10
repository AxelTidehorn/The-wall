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

                <section> <!--class="profileDescription"--> <!-- We better make this an include or something if we keep this structure. -->
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
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
