<?php //Some kind of search functionality based on the comment structure currently.
    include("backend/connect.php");

    @ session_start();

    if (isset($_SESSION["username"])) {
        $sessionUser = $_SESSION["username"];
    } else {
        $sessionUser = false;
    }

    if (!isset($_SESSION["updatedRating"])) $_SESSION["updatedRating"] = -1;

    include ("config.php");
    include("backend/connect.php");

    //Seeing if this is a general search from the searchfield, or a more andvanced search! (will be added later)

    //print'<div id="searchPage">';

    $query = $conn->prepare("SELECT likedContent FROM Users WHERE id = " . $_SESSION["user_id"]);
    $query->bind_result($likedContent);
    $query->execute();
    $query->fetch();
    $where = "";

    if ($likedContent) {
        $likedArray = explode("/", $likedContent);
        $where = " WHERE id = " . reset($likedArray);

        for ($i = 1; $i < sizeof($likedArray); $i++) {
            $where = $where . " OR id = " . $likedArray[$i];
        }

        $query->close();

        $count = 0;
        $query = $conn->prepare("SELECT * FROM `Content`" . $where);
        $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
        $query->store_result();
        $query->bind_result($id, $contentType, $publisher, $name, $url, $image, $webbsite, $text, $nsfw, $publicDomain, $rating, $date, $views, $description, $tags, $editorsChoise);

        //    trying to create a associative array with all the content. This is how im used to working.
        while ($query->fetch()) {
            $count++;
            $contentArray[$count] = array('ID' => $id, 'type' => $contentType, 'publisherID' => $publisher, 'name' => $name, 'url' => $url, 'image' => $image, 'webbsite' => $webbsite, 'text' => $text, 'nsfw' => $nsfw, 'publicDomain' => $publicDomain, 'rating' => $rating, 'date' => $date, 'views' => $views, 'description' => $description, 'tags' => $tags);
        }

        $query->close();

        if($contentArray != null){
            print'<h2>Liked content</h2>';
        }

        if ($sessionUser) {
            $query = $conn->prepare("SELECT likedContent FROM Users WHERE username = '{$sessionUser}'");
            $query->bind_result($liked);
            $query->execute();
            $query->fetch();
            $query->close();
        }

        foreach ($contentArray as $content) {
            $query = $conn->prepare("SELECT username FROM Users WHERE id = '" . $content['publisherID'] . "'");
            $query->bind_result($publisherName);
            $query->execute();
            $query->fetch();

            $image = base64_encode(stripslashes($content['image']));
            $webbsite = base64_encode(stripslashes($content['webbsite']));
            $id = $content['ID'];

            include("includes/likeHandler.php");

            include("includes/contentRenderer.php");
        }
    }
?>
