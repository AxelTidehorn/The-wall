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

                <section class="profileDescription">
                    <?php //Connect to DB, fetch saved username value from session cookie, fetch information through SQL query and display information.
                        include("backend/connect.php");
                        $username = $_SESSION["username"];
                        $query = $conn->prepare("SELECT joinDate FROM Users WHERE username = '{$username}'");
                        $query->bind_result($joinDate);
                        $query->execute();

                        while ($query->fetch()) {
                            echo '
                                <h1>' . $username . '</h1>
                                <span>Joined ' . $joinDate . '</span>
                            ';
                        }

                        $query->close();
                    ?>

                    <div class="infoBox">
                        <span>Name: Axel</span>
                        <span>Gender: Axel</span>
                        <span>Age: Axel</span>
                    </div>

                    <p>Axel is axel. Axel. Axeeeeeeeeeeeeeeeeeeel. Lorem ipsum. Placeholder textAxeeeeeeeeeeeeeeeeeeel. Lorem ipsum. Placeholder textAxeeeeeeeeeeeeeeeeeeel. Lorem ipsum. Placeholder textAxeeeeeeeeeeeeeeeeeeel. Lorem ipsum. Placeholder textAxeeeeeeeeeeeeeeeeeeel. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text. Lorem ipsum. Placeholder text.</p>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
