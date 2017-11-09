<?php
print'I will come to this stage, probably tomorrow! </br> Here is also where I will handle image compression. </br>';
include_once "connect.php";
$uploadedForm = $_POST;
var_dump($uploadedForm);
//var_dump($_FILES['uploadedImage']['tmp_name']);
//Checking what type of upload the user posted.
switch ($uploadedForm['uploadType']) { //test

    case 'image':
//        imageCompression($uploadedForm['uploadedImage']);
        $imageLocation = $_FILES['uploadedImage']['tmp_name'];
        $compressedImage = compress_image($imageLocation);
//        var_dump($compressedImage);

        //uploading everything to the database

        $name = $uploadedForm['contentName'];
        $description = $uploadedForm['contentDescription'];
        $publicDomain = $uploadedForm['publicDomain'];
        $nsfw = $uploadedForm['NSFW'];
        $date = date("Y-m-d"); //Year, month, day, different capitalization can display the date differently
        $uploadType = $uploadedForm['uploadType'];
        $uploaderId = 1; //This will do for now!

        $conn->prepare("INSERT INTO `Content`( `content_type`, `Publisher`, `Name`, `ContentImage`, `NSFW`, `PublicDomain`, `Date`, `Description`) VALUES (?,?,?,?,?,?,?,?)");

        $query->bind_param("sssbddss", $uploadType, $uploaderId , $name, $compressedImage, $nsfw, $publicDomain, $date, $description);
        $query->execute();
        $query->close();

        break;

    case 'text':


        break;

    case 'webbsite':

        $imageLocation = $_FILES['uploadedImage']['tmp_name'];
        $compressedImage = compress_image($imageLocation);
        break;
}

//This part handles the image compression!
function compress_image($source_url) {

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

    $destination_url = imagejpeg($created_image, NULL, 85);
    base64_encode($destination_url);
    return $destination_url;
};


/**
 * Created by PhpStorm.
 * User: Linus
 * Date: 2017-11-06
 * Time: 13:00
 */
