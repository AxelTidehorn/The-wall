<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php include "head.php" ?>

    <body>
        <div id="pageContainer">
          <?php include 'header.php'; ?>

          <main>
            <?php
                if (isset($_GET["remove"])) {
                    setcookie("welcomed", true, time() + 60*60*24*30);
                    echo '<script src="js/hide.js"></script>';
                } else if (!isset($_COOKIE["welcomed"])) {
                    echo '
                        <section id="welcome">
                            <p>Welcome to The Wall. Here you can share your creations and view the creations of others. By using this site you agree to our usage of cookies.</p>
                            <div class="hide">
                                <a href="' . $currentPage . '?remove=true">Hide</a>
                            </div>
                        </section>
                    ';
                }
            ?>

                <div id="newest">
                  <div class="center-text">
                    <h2><a href="#">Newest Content</a></h2>
                  </div>

                    <?php
                        include "includes/db_connect.php";
                        $sql = $mysqli->prepare("SELECT Username, ContentImage FROM Content
                                                JOIN Users ON Content.Publisher = Users.ID");
                        $sql->bind_result($publisher, $contentImage);
                        $sql->execute();

                        while($sql->fetch()) { //How we do dis? Display blob images.
                            echo '
                                <div class="contentcont">
                                    <a href="#"><img src="' . $contentImage . '" alt="an excellent picture"></a>
                                    <div class="actioncont">
                                      <div class="profilecont">
                                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                                        <a class="profilename" href="LINK-TO-PROFILE">' . $publisher . '</a>
                                      </div>
                                      <div class="buttoncont">
                                        <a class="likebtn" href="#">LIKE</a>
                                      </div>
                                    </div>
                                </div>
                            ';
                        }

                        $sql->close();
                    ?>

                  <div class="contentcont">
                    <a href="content.php"><img src="imgs/An_Excellent_JPEG2.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">Chef Excellence</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>

                  <div class="contentcont">
                    <a href="#"><img src="imgs/kebabpizza.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">A. Whitedude</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>
              </div>

                <div id="topRated">
                  <div class="center-text">
                    <h2><a href="#">Top Rated Content</a></h2>
                  </div>

                  <div class="contentcont">
                    <a href="#"><img src="imgs/An_Excellent_JPEG2.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">Chef Excellence</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>

                  <div class="contentcont">
                    <a href="#"><img src="imgs/kebabpizza.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">A. Whitedude</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>
              </div>

                <div id="editorsChoice">
                  <div class="center-text">
                    <h2><a href="#">Editor's Choice</a></h2>
                  </div>

                  <div class="contentcont">
                    <a href="#"><img src="imgs/An_Excellent_JPEG2.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">Chef Excellence</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>

                  <div class="contentcont">
                    <a href="#"><img src="imgs/kebabpizza.jpg" alt="an excellent picture"></a>
                    <div class="actioncont">
                      <div class="profilecont">
                        <a href="#" class="profilethumb"><img src="imgs/axel.jpg" alt="profilethumb"></a>
                        <a class="profilename" href="LINK-TO-PROFILE">A. Whitedude</a>
                      </div>
                      <div class="buttoncont">
                        <a class="likebtn" href="#">LIKE</a>
                      </div>
                    </div>
                  </div>
              </div>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
