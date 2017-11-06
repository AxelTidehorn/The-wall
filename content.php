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
                <form>
                    <label>Make a commment</label>
                    <textarea class="commentBox"></textarea>
                    <input type="submit" value="Send"></input>
                </form>
                <h2>Comments</h2>
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
