<header>
    <nav class="navBar">
        <div class="icon"><a href="index.php"><i class="fa fa-home fa-3x"></i></a></div>
        <div class="icon"><a href="#" id="searchbtn"><i class="fa fa-search fa-3x"></i></a></div>
        <form method="GET" action="search.php" class="searchBar" id="searchpls">
            <input class="search" type="text" name="generalSearch" placeholder="Search...">
        </form>
        <div class="icon"><a href="upload.php"><i class="fa fa-upload fa-3x"></i></a></div>
        <?php
        session_start();
        if(isset($_SESSION['user_id'])){
            print '<div class="icon"><a href="user.php?user_ID='.$_SESSION["user_id"].'"><i class="fa fa-user fa-3x loggedIn"></i></a></div>';
        }
        else{
            print '<div class="icon"><a href="login.php"><i class="fa fa-user fa-3x"></i></a></div>';
        }
        ?>
        <!--        <div class="icon"><a href="users.php"><i class="fa fa-user fa-3x --><?php //echo (isset($_SESSION['username'])) ? "loggedIn" : NULL ?><!--"></i></a></div>-->
        <!--<div class="icon"><a href="#"><i class="fa fa-question-circle-o fa-3x"></i></a></div>-->
    </nav>
</header>