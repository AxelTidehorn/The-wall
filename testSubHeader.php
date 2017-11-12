<?php
include_once 'config.php';
if (isset($_GET['user_ID'])) {
    if ($_GET['user_ID'] == $_SESSION['user_id']) {
        //This header checks the user id provided in the _GET and determines if you are looking at your own profile or not.
        print'
        <div class="subHeader">
            <div class="subHeadItem"><a href='.$baseURL.'>Profile</a></div>
            <div class="subHeadItem"><a href='.$baseURL."&friends".'>Friends</a></div>
            <div class="subHeadItem"><a href="'.$baseURL."&posts".'">Posts</a></div>
            <div class="subHeadItem"><a href='.$baseURL."&liked".'>Liked</a></div>
            <div class="subHeadItem"><a href="logout.php">Log Out</a></div>
        </div>
        ';
    } else {

        print'
        <div class="subHeader">
            <div class="subHeadItem"><a href='.$baseURL.'>Profile</a></div>
            <div class="subHeadItem"><a href='.$baseURL."&friends".'>Friends</a></div>
            <div class="subHeadItem"><a href="'.$baseURL."&posts".'">Posts</a></div>
            <div class="subHeadItem"><a href='.$baseURL."&liked".'>Liked</a></div>
        </div>';
    }
    //This will output nothing. only used if there is no _GET present, and that will only be when u explore all users.
    //Might create a way to add friends and se your friendlist here actually
} else {

}

?>
