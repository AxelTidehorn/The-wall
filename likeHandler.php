<?php
    if (isset($_GET["like"])) {
        $contentRequest = $_GET["like"];

        if ($liked) {
            $likedArray = explode("/", $liked);
            $alreadyLiked = false;
            foreach ($likedArray as $likedContent) {
                if ($likedContent === $contentRequest) {
                    $alreadyLiked = true;
                }
            }

            if (!$alreadyLiked) {
                $liked = $liked . "/" . $contentRequest;
            }
        } else {
            $liked = $contentRequest;
        }

        include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
        $query = $conn->prepare("UPDATE Users SET likedContent = ? WHERE username = '{$sessionUser}'");
        $query->bind_param("s", $liked);
        $query->execute();
        $query->close();

        if (!$GLOBALS["updatedRating"] && $contentRequest == $content["ID"]) {
            $query = $conn->prepare("UPDATE Content SET rating = ? WHERE ID = '{$contentRequest}'");
            $ratingParam = $content["rating"] + 1;
            $query->bind_param("i", $ratingParam);
            $query->execute();
            $query->close();

            $query = $conn->prepare("SELECT rating FROM Content WHERE ID = '{$contentRequest}'");
            $query->bind_result($rating);
            $query->execute();
            $query->fetch();

            $content["rating"] = $rating;
            $GLOBALS["updatedRating"] = true;
        }
    }

    if (isset($_GET["unlike"])) {
        $contentRequest = $_GET["unlike"];

        if ($liked) {
            if (!$GLOBALS["updatedRating"] && $contentRequest == $content["ID"]) {
                $likedArray = explode("/", $liked);
                $searchId = array_search($contentRequest, $likedArray);
                unset($likedArray[$searchId]); //Removes that part of the array.

                $newLikedArray = array();

                foreach ($likedArray as $likedContent) { //Uses the old array parts to create a new array with slashes inbetween names.
                    $newLikedArray[] = $likedContent . "/";
                }

                $liked = implode($newLikedArray); //Makes it into a string again.
                $liked = substr($liked, 0, strlen($liked) - 1); //Starts the string and takes all of it except the last character to skip the last "/"

                include("backend/connect.php"); //I'm not sure why it seems like we have to reconnect to the db here...
                $query = $conn->prepare("UPDATE Users SET likedContent = ? WHERE username = '{$sessionUser}'");
                $query->bind_param("s", $liked);
                $query->execute();
                $query->close();

                $query = $conn->prepare("UPDATE Content SET rating = ? WHERE ID = '{$contentRequest}'");
                $rating = $content["rating"] - 1;
                $query->bind_param("i", $rating);
                $query->execute();
                $query->close();

                $query = $conn->prepare("SELECT rating FROM Content WHERE ID = '{$contentRequest}'");
                $query->bind_result($rating);
                $query->execute();
                $query->fetch();

                $content["rating"] = $rating;
                $GLOBALS["updatedRating"] = true;
            }
        }
    }

    include("backend/connect.php");

    if ($sessionUser !== false) {
        include("config.php");

        if (!(strpos($currentURI, "?") === false || !isset($_GET["like"]) || !isset($_GET["unlike"]))) {
            $symbol = "&";
        } else {
            $symbol = "?";
        }

        $link = explode($symbol, $currentURI); //Prepares the link that will be used on the add/remove contact buttons and preventing the URI from ending up in an endless sequence of GETs in the link.

        if ($link === array($currentURI)) { //Explode makes it into an array, so compare with the URI as array, handle the link differently if there is no addContact or whatever GET in the link already.
            $noChange = true;
        } else {
            $noChange = false;
        }

        if ($liked) {
            $likedArray = explode("/", $liked);
            $alreadyLiked = false;
            foreach ($likedArray as $likedContent) {
                if ($likedContent == $id) {
                    $alreadyLiked = true;
                }
            }

            if ($alreadyLiked) {
                $end = $symbol . "unlike=" . $id;
                $likeString = "Unlike";
                $class = "unlikebtn";
            } else {
                $end = $symbol . "like=" . $id;
                $likeString = "Like";
                $class = "likebtn";
            }
        } else {
            $end = $symbol . "like=" . $id;
            $likeString = "Like";
            $class = "likebtn";
        }

        if ($noChange) {
            $link[] = $end;
        } else {
            $link[sizeof($link) - 1] = $end; //Replaces the last part of the array.
        }

        $link = implode($link); //Make it a string again
    }
?>
