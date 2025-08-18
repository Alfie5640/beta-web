<?php
    session_start();

    include('dbConnect.php');

//php functions instantiated 
    

    function sanitiseInputs() {
        $_SESSION['errorMessage'] = "";
        if($_POST['username'] != filter_var($_POST['username'], @FILTER_SANITIZE_STRING)) {
            $_SESSION['errorMessage'] = "<p>Non-conforming characters in the username field.</p><p>Please review and re-enter this field</p>";
        } else {
            if($_POST['password'] != filter_var($_POST['password'], @FILTER_SANITIZE_STRING)) {
                $_SESSION['errorMessage'] = "<p>Non-conforming characters in the password field.</p><p>Please review and re-enter this field</p>";
            }
        }
    }


    function addUser($link, $username, $pass, $role) {
        
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO EndUser (username, `role`, password_hash) VALUES ('$username', '$role', '$hashedPass');";
        
        if (!mysqli_query($link, $sql)) {
        echo "Error: " . mysqli_error($link) . "<br>";
        echo "Query: " . $sql;
    } else {
        header("Location: login.php");
    }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <script src=""></script>
    <div id="maincontent">
        <h1>REGISTER</h1>
        <br>
        <div id="login-box">

            <div class="login-form">
                <!-- login form -->

                <form method="post" id="registerForm">

                    <div class="inputWrapper">
                        <p>Username:</p>
                        <input id="username" type="text" placeholder="Enter username" required />
                    </div>

                    <div class="inputWrapper">
                        <p>Password:</p>
                        <input id="password" type="password" placeholder="Enter password" required>
                    </div>

                    <div class="inputWrapper" id="radio">
                        <p>Role:</p>
                        <label for="climber">Climber</label>
                        <input type="radio" name="role" value="Climber" id="climber" required>

                        <label for="instructor">Instructor</label>
                        <input type="radio" name="role" value="Instructor" id="instructor">
                    </div>

                    <div class="submitWrapper">
                        <p></p>
                        <input type="submit" value="Register" class="submitButton">
                    </div>
                </form>
                <div id="registerBox">
                    <a href="login.php">
                        <p id="register">Already have an account? Login here...</p>
                    </a>
                </div>
            </div>
        </div>
        <div id="error">
            <script src="js/register.js"></script>
        </div>
    </div>
</body>

</html>
