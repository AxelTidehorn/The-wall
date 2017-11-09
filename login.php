<?php
    if (isset($_SESSION["user_id"])) {
        header("location:index.php");
    }
?>

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
                <section>
                    <h2>Log in</h2>
                    <form method="POST">
                        <label>Username</label>
                        <input type="text" name="username" />
                        <label>Password</label>
                        <input type="password" name="password" />
                        <input type="submit" value="Log in" />
                    </form>

                    <a href="register.php">Do you not have an account? Register here!</a>

                    <?php
                        if (isset($_POST) && !empty($_POST)) { //If something has been sent through the form basically
                            $usernameInput = $_POST["username"];
                            $usernameInput = mysqli_real_escape_string($conn, $usernameInput);
                            $usernameInput = htmlentities($usernameInput);

                            $passwordInput = sha1($_POST["password"]); //Hash inputted password to later compare it in the SQL query
                            $passwordInput = mysqli_real_escape_string($conn, $passwordInput);
                            $passwordInput = htmlentities($passwordInput);

                            $query = $conn->prepare("SELECT username, password FROM Users WHERE username = '{$usernameInput}' AND password = '{$passwordInput}'");
                            $query->execute(); //Selecting both username and password may be redundant here as we are not really using that information apart from checking if there is some information.
                            $query->store_result();

                            if ($query->num_rows()) { //If there are more than 0 numbers of rows (a match), the user should exist
                                $query = $conn->prepare("SELECT id FROM Users WHERE username = '{$usernameInput}'");
                                $query->bind_result($id);
                                $query->execute();
                                $query->fetch();

                                ini_set("session.cookie_httponly", true);
                                session_start();

                                if (isset($_SESSION["userIP"]) === false) {
                                    $_SESSION["userIP"] = $_SERVER["REMOTE_ADDR"];
                                }

                                if ($_SESSION["userIP"] !== $_SERVER["REMOTE_ADDR"]) {
                                    session_unset();
                                    session_destroy();
                                }

                                $_SESSION["username"] = $usernameInput;
                                $_SESSION["user_id"] = $id;
                                header("location:userProfile.php");
                            } else {
                                echo "<p>The entered username and password does not match a user in the database.";
                            }
                        }
                    ?>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
