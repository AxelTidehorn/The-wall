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
                <?php //Loop through the comments and add them (might not work yet either)
                    include("backend/connect.php");

                    //$search = "test";
                    $search = $_GET["search"];
                    $query = $conn->prepare("SELECT username FROM Users WHERE username LIKE '%" . $search . "%'"); //Where it is for the right content or something perhaps in the future.
                    $query->bind_result($username);
                    $query->execute();

                    while ($query->fetch()) {
                        echo '
                            <div class="comment">
                                <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                <a href="#" class="profilename">' . $username . '</a>
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
