<?php include "config.php" ?>
<!DOCTYPE html>
<html>
<?php
include "head.php" ;
include_once "backend/connect.php";
?>

<body>
<div id="pageContainer">
    <?php include 'testHead.php'; ?>
    <main>
        <?php
        @ session_start();
        if (!isset($_COOKIE["welcomed"])) { //Display the welcome message if the user has not been welcomed, and include a script to determine if it has been clicked.
            echo '
                        <section id="welcome">
                            <p>Welcome to The Wall. Here you can share your creations and view the creations of others. By using this site you agree to our usage of cookies.</p>
                            <div class="hide">
                                <a href="#">Hide</a>
                            </div>
                        </section>

                        <script src="js/hide.js"></script>
                    ';
        }
//        var_dump($_SESSION);
        if (!isset($_SESSION['user_id'])){
            header("location:login.php");

        }
        ?>
        <section>
        <div class="upload">
            <h2>Please select the type of content you wish to upload!</h2>
            <form class="typeSelector">

                <label class="uploadStyle" for="selectedImage"> Image
                  <input class="input" type="radio" name="typeSelection" value="Image" id="selectedImage" onclick="showImg()">
                  <span class="checkmark"></span>
                </label>

                <label class="uploadStyle" for="selectedText"> Text
                  <input class="input" type="radio" name="typeSelection" value="Text" id="selectedText" onclick="showTxt()">
                  <span class="checkmark"></span>
                </label>

                <label class="uploadStyle" for="selectedWebsite"> Website
                  <input class="input" type="radio" name="typeSelection" value="Website" id="selectedWebsite" onclick="showWeb()">
                  <span class="checkmark"></span>
                </label>
            </form>
        </div>
        <div id="uploadForm">
            <form action="backend/handler.php" id="imageform" method="post" enctype="multipart/form-data">
                <input type="hidden" name="uploadType" value="">
                <input id="titleInput" type="text"  name="contentName" placeholder="Title" required>
                <input class="uploadBtn" type="file" name="uploadedImage" placeholder="image" >
                <input class="uploadBtn" type="file" name="uploadedWebsite" placeholder="webbsite" >
                <textarea name="uploadedText" id="" cols="30" rows="10" placeholder="Write your text-based content here" form="imageform"></textarea>

                <input id="uploadUrl" type="text" name="URL" placeholder="URL of your website">

                <h3>Does the content contain anything NSFW?</h3>
                <div class="uploadFormCont">
                  <label class="uploadStyle">Yes
                    <input class="input" type="radio" name="NSFW" value="1">
                    <span class="checkmark"></span>
                  </label>
                  <label class="uploadStyle">No
                    <input class="input" type="radio" name="NSFW" value="0">
                    <span class="checkmark"></span>
                  </label>
                </div>

                <h3>Do you want to publish this content as a Public Domain piece?</h3>
                <div class="uploadFormCont">
                  <label class="uploadStyle">Yes
                    <input class="input" type="radio" name="publicDomain" value="1">
                    <span class="checkmark"></span>
                  </label>
                  <label class="uploadStyle">No
                    <input class="input" type="radio" name="publicDomain" value="0">
                    <span class="checkmark"></span>
                  </label>
                </div>

                  <input type="text" name="contentDescription" placeholder="Write a description of your content!">

<!--                Tag system-->
                <span class="tags">Tags:</span>
                <textarea name="tagData"  id="tagData" form="imageform" style="display:none;"></textarea>
                <div id="tagShowing"></div>
                <input type="text" id="tagInput" class="tag_part" placeholder="Tags, one at the time"/>
                <input type="button" class="tagSubmit" value="enter" onclick="createTag()"/>


                <span class="termsOfUse">By uploading you agree to our none-existing terms of use! (We own this now)</span>
                <input type="submit">
            </form>
        </div>
        <script src="js/uploadFormHandler.js"></script>
      </section>
    </main>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
