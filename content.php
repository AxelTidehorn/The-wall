<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php include "head.php" ?>

    <body>
        <div id="pageContainer" class="contentPage">
          <div class="contentcont">
            <a href="index.php" class="img"><img src="imgs/An_Excellent_JPEG2.jpg" alt="an excellent picture"></a>
            <div class="backButton"><a href="index.php">Back</a></div> <!-- Realized we might not need this here, but could possibly use it elsewhere if needed. -->
            <div class="actioncont contentInfo">
              <div class="profilecont">
                <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                <a class="profilename" href="LINK-TO-PROFILE">Chef Excellence</a>
              </div>
              <div class="buttoncont">
                <a class="likebtn" href="#">LIKE</a>
              </div>
            </div>
            <section>
                <h2>Description</h2>
                <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
            </section>
            <section class="comments">
                <form method="POST">
                    <label>Make a commment</label>
                    <textarea class="commentBox" name="comment"><?php //No gap between textarea and php in order to elimate spacing in the text area when you echo.
                            include("backend/connect.php");

                            session_start();

                            //If you are trying to post a comment and if you are logged in, post a comment to the database basically. (unfinished)
                            if (isset($_SESSION["username"]) && isset($_POST["comment"]) && !empty($_POST["comment"])) {
                                $username = $_SESSION["username"];
                                $query = $conn->prepare("INSERT INTO Comments (publisher, date, comment) VALUES(?, ?, ?)"); //Lack of content foreign key might be the cause of it not working currently.
                                $date = date("Y-m-d");
                                $query->bind_param("sss", $username, $date, $_POST["comment"]);
                                $query->execute();
                            } else if (!isset($_SESSION["username"])) {
                                echo 'Log in to comment on content.';
                            }
                    ?></textarea>
                    <input type="submit" value="Publish comment"></input>
                </form>
                <h2>Comments</h2>
                <?php //Loop through the comments and add them (might not work yet either)
                    $query = $conn->prepare("SELECT publisher, date, comment FROM Comments"); //Where it is for the right content or something perhaps in the future.
                    $query->bind_result($publisher, $date, $comment);
                    $query->execute();

                    while ($query->fetch()) {
                        echo '
                            <div class="comment">
                                <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                <a href="#" class="profilename">' . $publisher . '</a>
                                <p>' . $comment . '</p>
                                <span>' . $date . '</span>
                            </div>
                        ';
                    }

                    $query->close();
                ?>
                <div class="comment">
                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                    <a href="#" class="profilename">Axel</a>
                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                </div>
                <div class="comment">
                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                    <a href="#" class="profilename">Axel</a>
                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                </div>
                <div class="comment">
                    <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                    <a href="#" class="profilename">Axel</a>
                    <p>Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum</p>
                </div>
            </section>
          </div>
        </div>
    </body>
</html>
