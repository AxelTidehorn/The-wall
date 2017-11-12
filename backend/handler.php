<?php
session_start();
print'I will come to this stage, probably tomorrow! </br> Here is also where I will handle image compression. </br>';
include_once "connect.php";
$uploadedForm = $_POST;
//var_dump($uploadedForm);
//var_dump($_FILES['uploadedImage']['tmp_name']);
//Checking what type of upload the user posted.
switch ($uploadedForm['uploadType']) { //test

    case 'image':
//        imageCompression($uploadedForm['uploadedImage']);
        $imageLocation = $_FILES['uploadedImage']['tmp_name'];
        $url = "destination .jpg";
        compress_image($imageLocation, $url);



//        var_dump($compressedImage);

        //uploading everything to the database

        $name = $uploadedForm['contentName'];
        $description = $uploadedForm['contentDescription'];
        $publicDomain = $uploadedForm['PublicDomain'];
        $nsfw = $uploadedForm['NSFW'];
        $date = date("Y-m-d"); //Year, month, day, different capitalization can display the date differently
        $uploadType = $uploadedForm['uploadType'];
        $tags = $uploadedForm['tagData'];
        $imageToUpload = addslashes((file_get_contents($url)));
//        var_dump("sssiddsss". $uploadType. $_SESSION["user_id"] . $name. $compressedImage. $nsfw. $publicDomain. $date. $description. $tags);
        $query = $conn->prepare("INSERT INTO `Content`( `content_type`, `Publisher`, `Name`, `ContentImage`, `NSFW`, `PublicDomain`, `Date`, `Description`, `tags`) VALUES (?,?,?,?,?,?,?,?,?)");


        $query->bind_param("ssssddsss", $uploadType, $_SESSION["user_id"] , $name, $imageToUpload, $nsfw, $publicDomain, $date, $description, $tags);
        $query->execute();
        $query->close();
        imagedestroy($url);
        break;

    case 'text':

        //uploading everything to the database)
        $imageLocation = parse_url("../imgs/text.png");
        $url = "destination .jpg";

        $name = $uploadedForm['contentName'];
        $description = $uploadedForm['contentDescription'];
        $publicDomain = $uploadedForm['publicDomain'];
        $contentText = $uploadedForm['uploadedText'];
        $nsfw = $uploadedForm['NSFW'];
        $date = date("Y-m-d"); //Year, month, day, different capitalization can display the date differently
        $uploadType = $uploadedForm['uploadType'];
        $tags = $uploadedForm['tagData'];
        /*$imageToUpload = addslashes((file_get_contents($url)));*/
        //$created_image = imagecreatefrompng("../imgs/text.png");
        //$imageToUpload = imagejpeg($created_image, "destination .jpg", 85);
        $imageToUpload = addslashes(file_get_contents("./test.png"));
        unset($query);
        $query = $conn->prepare("INSERT INTO `Content`( `content_type`, `Publisher`, `Name` , `ContentImage`, `ContentText`, `NSFW`, `PublicDomain`, `Date`, `Description`, `tags`) VALUES (?,?,?,?,?,?,?,?,?)");

        $query->bind_param("sssssddsss", $uploadType, $_SESSION["user_id"] , $name, $imageToUpload ,$contentText, $nsfw, $publicDomain, $date, $description, $tags);
        $query->execute();
        $query->close();

        break;

    case 'website':

        //        imageCompression($uploadedForm['uploadedImage']);
        $imageLocation = $_FILES['uploadedWebsite']['tmp_name'];
        $url = "destination.jpg";
        $compressedImage = compress_image($imageLocation, $url);
        $fileContent = addslashes(file_get_contents($url));
//        var_dump($compressedImage);

        //uploading everything to the database

        $name = $uploadedForm['contentName'];
        $description = $uploadedForm['contentDescription'];
        $publicDomain = $uploadedForm['publicDomain'];
        $nsfw = $uploadedForm['NSFW'];
        $date = date("Y-m-d"); //Year, month, day, different capitalization can display the date differently
        $uploadType = $uploadedForm['uploadType'];
        $tags = $uploadedForm['tagData'];
        $url = $uploadedForm['URL'];

        $query = $conn->prepare("INSERT INTO `Content`( `content_type`, `Publisher`, `Name`, `URL` ,`ContentWebbsite`, `NSFW`, `PublicDomain`, `Date`, `Description`, `tags`) VALUES (?,?,?,?,?,?,?,?,?,?)");

        $query->bind_param("sssssddsss", $uploadType, $_SESSION["user_id"] , $name , $url ,$fileContent, $nsfw, $publicDomain, $date, $description, $tags);

        /*$query = $conn->prepare("INSERT INTO `Content`( `content_type`, `Publisher`, `Name`, `URL` ,`ContentWebbsite`, `NSFW`, `PublicDomain`, `Date`, `Description`, `tags`) VALUES (?,?,?,?,?,?,?,?,?,?)");

        $query->bind_param("sssssddsss", $uploadType, $_SESSION["user_id"] , $name , $url ,$fileContent, $nsfw, $publicDomain, $date, $description, $tags);*/
        $query->execute();
        $query->close();

        break;
}

//This part handles the image compression!
function compress_image($source_url, $return) {

    //We could do some cropping, resizing, and watermarking if we wanted to
    //here is a code snippet to determine the size of the image for example
//elseif (($image_width < 450) || ($image_height < 450)) {
//    print "The selected image is too small, please select a larger";
//} elseif (($image_width >= 450) && ($image_height >= 450)) {
    //compressing the image

    $info = getimagesize($source_url);


    if ($info['mime'] == 'image/jpeg')
        $created_image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $created_image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $created_image = imagecreatefrompng($source_url);

    imagejpeg($created_image, $return, 85);

    return $return;
};


/**
 * Created by PhpStorm.
 * User: Linus
 * Date: 2017-11-06
 * Time: 13:00
 */
