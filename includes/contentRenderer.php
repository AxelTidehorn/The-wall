<?php
    print"
        <div class='contentcont'>
            <form action='post.php' method='get' class='form' id='" . $content["ID"] . "'>
                <input type='hidden' value='" . $id . "' name='post'/>
                <div onclick='this.parentNode.submit();'>";
                    if ($content["type"] == "text") {
                        echo "<img class='linkImg' src='imgs/text.png'/>";
                    } else if ($content["type"] == "website") {
                        echo "<img class='linkImg' src='data:image/jpeg;base64," . $webbsite . "'/>";
                    } else {
                        echo "<img class='linkImg' src='data:image/jpeg;base64," . $image . "'/>";
                    }
                echo "</div>
            </form>
            <div class='actioncont'>
                <div class='contentName'><a href='post.php?post=" . $id . "'>" . $content["name"] . "</a></div>

                <div class='contentBox'>
                    <div class='profilecont'>
                        <a href='#' class='profilethumb'><img src='imgs/axel.jpg' alt='profilethumb'></a>
                        <a class='profilename' href='user.php?user_ID=" . $content['publisherID'] . "'>" . $publisherName . "</a>
                    </div>
                    <form method='GET' class='buttoncont'>";
                        if ($currentPage == "post.php") {
                            echo "<input type='hidden' name='post' value='" . $_GET["post"] . "' />";
                        }
                        if ($currentPage == "user.php") {
                            echo "<input type='hidden' name='user_ID' value='" . $_GET["user_ID"] . "' />";
                            echo "<input type='hidden' name='liked' />";
                        }
                        if ($currentPage == "search.php" && isset($_GET["generalSearch"])) {
                            echo "<input type='hidden' name='generalSearch' value='" . $_GET["generalSearch"] . "' />";
                        }
                        if ($currentPage == "search.php" && isset($_GET["advancedSearch"])) {
                            echo "<input type='hidden' name='advancedSearch' value='" . $_GET["advancedSearch"] . "' />";

                            if (isset($_GET["imageSearch"])) {
                                echo "<input type='hidden' name='imageSearch' value='" . $_GET["imageSearch"] . "' />";
                            }
                            if (isset($_GET["TextSearch"])) {
                                echo "<input type='hidden' name='TextSearch' value='" . $_GET["TextSearch"] . "' />";
                            }
                            if (isset($_GET["WebbsiteSearch"])) {
                                echo "<input type='hidden' name='WebbsiteSearch' value='" . $_GET["WebbsiteSearch"] . "' />";
                            }
                        }

                        echo "<input type='hidden' name='" . $name . "' value='" . $content["ID"] . "' />";

                        if (isset($_SESSION["user_id"])) {
                            echo "<input type='submit' class='" . $class . "' value='" . $likeString . " (" . $content["rating"] . ")' />";
                        } else {
                            echo "<a href='login.php' class='" . $class . "'/>" . $likeString . "</a>";
                        }
                    echo "</form>
                </div>
            </div>
        </div>
        <script src='js/animate.js'></script>
    ";
?>
