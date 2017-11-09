<<<<<<< HEAD
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

#print_r($_FILES['upload']);
/*
 * The global $_FILES will contain all the uploaded file information.
 * Its contents from the example form is as follows. Note that this assumes
 * the use of the file upload name userfile, as used in the example script above.
 * This can be any name.
 *
 * $_FILES['userfile']['name']
The original name of the file on the client machine.

$_FILES['userfile']['type']
The mime type of the file, if the browser provided this information. An example would be "image/gif". This mime type is however not checked on the PHP side and therefore don't take its value for granted.

$_FILES['userfile']['size']
The size, in bytes, of the uploaded file.

$_FILES['userfile']['tmp_name']
The temporary filename of the file in which the uploaded file was stored on the server.

$_FILES['userfile']['error']
The error code associated with this file upload.
 *
 *
 * Note:
Be sure your file upload form has attribute enctype="multipart/form-data" otherwise the file upload will not work.
 *
 *
 */

#important to only allow upload for files that don't affect your PHP code. (or only those files you need)
#after the user uploads the file, we basically run a few checks:

if (isset($_FILES['upload'])){

    #let's first make a whitelist of allowed extensions
    $allowedextensions = array('jpg', 'jpeg', 'gif', 'png');

    #now if the user uplaoded an allowed format, we want to know what format that was
    #the following variable will store the extension, all in lower-case
    #substr() is a function that takes only a portion of a string - we need only what comes after the dot
    #we need to get everything after the LAST dot, looking for the extension
    #strrpos returns the position of the last occurrence of a substring in a string
    #we use the file name and a dot to find the extension: strrpos($_FILES['upload']['name'], '.')
    #but we also need to add one space to ignore the dot, so we write +1 at the end.
    #strtolower function changes all capital letters to lower-string so JPG becomes jpg and it fits your whitelist
    #you should take the entire string and put it in 'strtolower'
    $extension = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.') + 1));

    #test by echoing out what you upload
    echo "Your file extension is: ".$extension;

    #we create an array called 'error' to store all our errors, so we can later use them.
    $error = array ();

    #here we do our first check, we basically want it to pass so we can upload.
    #if it does not pass, then we give an error.
    #we say, check to see if "externsions" can be found in our array "allowedextensions"
    #if the extension is NOT in the array, we return the error message (we actually add it into the array)
    if(in_array($extension, $allowedextensions) === false){

        #add a new array entry
        $error[] = 'This is not an image, upload is allowed only for images.';
    }

    #it is also good to think about the size of the file you want to accept.
    #this is for images, so how big of an image would you like to accept?
    #this is in bytes, and 1000000 is actually 1 mb which is now our limit
    if($_FILES['upload']['size'] > 1000000){

        $error[]='The file exceeded the upload limit';
    }


    #now you do the 'final check' to see if there are no errors in the array.
    #if they array is empty = no errors have been written in it.
    #if there is something in the array 'errors' that means an error has occured and we should not upload

    if(empty($error)){

        #this is our starding point, in order to upload we need to move the file (uploaded file)
        #all the way to the location we want to store it.
        #But, before we do so it will be good to do all of the ABOVE written first
        #We check for errors that might disturb our code, and try to avoid them
        #if there are no errrors move the file to the desired file location
        move_uploaded_file($_FILES['upload']['tmp_name'], "uploadedfiles/{$_FILES['upload']['name']}");
    }

}


?>


<html>
    <head>
        <title>Security - Upload</title>
           </head>

           <body>
               <div>
                   <?php

                   #Now we want to either upload the file or type an error
                   #what we do is basically  check if there's an array named 'error'
                   #and we check if it's empty. If it's empty that means no errors we found
                   #we should proceed with the upload.
                   if (isset($error)){
                       if (empty($error)){

                           #here we give the user the chance to check the file right away.
                           #this is just for testing purposes so we can see the file is there
                           #when the user clicks, it will open the folder "uploadedfiles" and look for filename
                           echo '<a href="uploadedfiles/' . $_FILES['upload']['name'] . '">Check file';

                       } else {
                           #else, if there was an error, then it simply goes through the error array
                           #it prints out ALL errors in the array.
                           #you can have one or more errors.
                           #e.g. File too big, AND not supported!
                           foreach ($error as $err){
                               echo $err;
                           }

                       }
                   }

                   ?>
               </div>

               <!-- This is our form, important to use "enctype="multipart/form-data"

               -->
               <div>

                   <form action="" method="POST" enctype="multipart/form-data">
                       <input type="file" name="upload" /></br>
                       <input  type="submit" value="submit" />
                   </form>
               </div>
           </body>




</html>
=======
<?php include "config.php" ?>
<!DOCTYPE html>
<html>
<?php
include "head.php" ;
include_once "backend/connect.php";
?>

<body>
<div id="pageContainer">
    <?php include 'header.php'; ?>
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
        <div class="upload">
            Please select the type of content you wish to upload!
            <form class="typeSelector">
                <input type="radio" name="typeSelection" value="Image" id="selectedImage" onclick="showImg()"><label for="selectedImage">Image</label>
                <input type="radio" name="typeSelection" value="Webbsite" id="selectedWebsite" onclick="showWeb()"><label for="selectedWebbsite">Webbsite</label>
                <input type="radio" name="typeSelection" value="Text" id="selectedText" onclick="showTxt()"><label for="selectedText">Text</label>
            </form>
        </div>
        <div id="uploadForm">
            <form action="backend/handler.php" id="imageform" method="post" enctype="multipart/form-data">
                <input type="hidden" name="uploadType" value="">
                <input type="text"  name="contentName" placeholder="Title" required>
                <input type="file" name="uploadedImage" placeholder="image" >
                <input type="file" name="uploadedWebsite" placeholder="webbsite" >
                <textarea name="uploadedText" id="" cols="30" rows="10" placeholder="Write your text-based content here" form="imageform"></textarea>
                <input type="text" name="URL" placeholder="URL of your website">
                Does the content contain anything NSFW?
                <input type="radio" name="NSFW" value="1">YES
                <input type="radio" name="NSFW" value="0">NO
                Do you want to publish this content as a Public Domain piece?
                <input type="radio" name="publicDomain" value="1">YES
                <input type="radio" name="PublicDomain" value="0">NO
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

    </main>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
<<<<<<< HEAD
>>>>>>> master
=======
>>>>>>> master
