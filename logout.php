<?php
    session_start();

    if (isset($_SESSION["user_id"])) { //If you are logged in, free all session variables, destroy data associated with session and redirect to login page
        session_unset();
        session_destroy();
        header("location:login.php");
    }
?>
