<?php include "config.php" ?>

<!DOCTYPE html>
<html>
    <?php
    include "head.php" ;
    ?>

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
                            if ($_POST["username"] != ""
                                && $_POST["password"] != ""
                                && $_POST["email"] != ""
                                && $_POST["password"] === $_POST["confirmPassword"]) {

                                registerUser();
                            } else {
                                echo "<p>Invalid user information. Ensure that the correct information has been entered.</p>";
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
                            $query->close();

                            //Start session engine, store the username as a session cookie and redirect to profile page (currently)
                            session_start();
                            $_SESSION["username"] = $username;
                            header("location:userProfile.php");
                        }
                    ?>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
