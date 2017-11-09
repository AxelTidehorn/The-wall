<?php include "config.php" ?>

<!DOCTYPE html>
<html lang="en">
    <?php include "head.php"; ?>

    <body>
        <div id="pageContainer">
          <?php include 'header.php'; ?>

            <main>
                <section>
                    <h2>Register</h2>
                    <form method="POST">
                        <label>Username</label>
                        <input type="text" name="username" />
                        <label>Password</label>
                        <input type="password" name="password" />
                        <label>Confirm password</label>
                        <input type="password" name="confirmPassword" />
                        <label>Email</label>
                        <input type="email" name="email" />
                        <input type="submit" value="Register" />
                    </form>

                    <?php
                        if (isset($_POST) && !empty($_POST)) {
                            evaluateInformation();
                        }

                        function evaluateInformation() { //Making sure that the information is "valid", may want to add more exceptions for usernames and possibly passwords.
                            $usernameInput = $_POST["username"];
                            $passwordInput = $_POST["password"];

                            $allowedUsernameCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-";
                            $allowedPasswordCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_!\"#¤%&/()=?@£\$€{[]}\',.-\\";
                            $usernameErrors = array();
                            $passwordErrors = array();
                            $otherErrors = array();

                            for ($iu = 0; $iu < strlen($usernameInput); $iu++) {
                                $validCharacter = false;
                                for ($ia = 0; $ia < strlen($allowedUsernameCharacters); $ia++) {
                                    if ($usernameInput[$iu] === $allowedUsernameCharacters[$ia]) {
                                        $validCharacter = true;
                                    }
                                }

                                if (!$validCharacter && !in_array($usernameInput[$iu], $usernameErrors)) {
                                    $usernameErrors[] = $usernameInput[$iu];
                                }
                            }

                            for ($ip = 0; $ip < strlen($passwordInput); $ip++) {
                                $validCharacter = false;
                                for ($ia = 0; $ia < strlen($allowedPasswordCharacters); $ia++) {
                                    if ($passwordInput[$ip] === $allowedPasswordCharacters[$ia]) {
                                        $validCharacter = true;
                                    }
                                }

                                if (!$validCharacter && !in_array($passwordInput[$iu], $passwordErrors)) {
                                    $passwordErrors[] = $passwordInput[$ip];
                                }
                            }

                            if ($_POST["username"] == "") {
                                $otherErrors[] = "Please enter a username.";
                            }

                            if ($_POST["password"] == "") {
                                $otherErrors[] = "Please enter a password.";
                            }

                            if ($_POST["email"] == "") {
                                $otherErrors[] = "Please enter an email.";
                            }

                            if ($_POST["password"] !== $_POST["confirmPassword"]) {
                                $otherErrors[] = "Your password and confirmed password does not match.";
                            }

                            if ($usernameErrors || $passwordErrors || $otherErrors) {
                                echo '<h2>Errors</h2>';

                                if ($usernameErrors) {
                                    echo '<p>Invalid characters for the username:</p>';
                                    foreach ($usernameErrors as $usernameError) {
                                        echo '<span>' . $usernameError . '</span><br>';
                                    }
                                }

                                if ($passwordErrors) {
                                    echo '<p>Invalid characters for the password:</p>';
                                    foreach ($passwordErrors as $passwordError) {
                                        echo '<span>' . $passwordError . '</span><br>';
                                    }
                                }

                                if ($otherErrors) {
                                    echo '<p>Other errors:</p>';
                                    foreach ($otherErrors as $otherError) {
                                        echo '<span>' . $otherError . '</span><br>';
                                    }
                                }
                            } else {
                                registerUser();
                            }
                        }

                        function registerUser() { //Connects to DB, prepares and inserts information along with the date
                            include_once "backend/connect.php";

                            $query = $conn->prepare("INSERT INTO Users (Username, Password, Email, JoinDate, LastActive) VALUES(?, ?, ?, ?, ?)");
                            $username = $_POST["username"];
                            $password = sha1($_POST["password"]);
                            $date = date("Y-m-d"); //Year, month, day, different capitalization can display the date differently
                            $query->bind_param("sssss", $username, $password, $_POST["email"], $date, $date);
                            $query->execute();

                            $query = $conn->prepare("SELECT id FROM Users WHERE username = '{$username}'");
                            $query->bind_result($id);
                            $query->execute();
                            $query->fetch();

                            $query->close();

                            //Start session engine, store the username as a session cookie and redirect to profile page (currently)
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["user_id"] = $id;
                            header("location:userProfile.php");
                        }
                    ?>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
