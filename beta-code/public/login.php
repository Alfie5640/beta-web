<?php

//php functions instantiated 

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <script src=""></script>
    <div id="maincontent">
        <h1>LOGIN</h1>
        <br>
        <div id="login-box">

            <div class="login-form">
                <!-- login form -->

                <form action="login.php" method="post">

                    <div class="inputWrapper">
                        <p>Username:</p>
                        <input name="username" type="text" placeholder="Enter username" required />
                    </div>

                    <div class="inputWrapper">
                        <p>Password:</p>
                        <input name="password" type="password" placeholder="Enter password" required>
                    </div>

                    <div class="submitWrapper">
                        <p></p>
                        <input type="submit" value="Login" class="submitButton">
                    </div>
                </form>
                <div id="registerBox">
                    <a href="register.php"><p id="register">Not registered? Create account here...</p></a>
                </div>
            </div>
        </div>
        <div id="error">
                <!-- form processing php here -->
                <p>error message</p>
            </div>
    </div>
</body>

</html>
