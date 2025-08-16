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
        <h1>Login</h1>
        <br>
        <div id="login-box">

            <div class="login-form">
                <!-- login form -->

                <form action="login.php" method="post">


                    <p>Username: </p>
                    <input name="username" type="text" placeholder="Enter username" required />

                    <p>Password: </p>
                    <input name="password " type="password" placeholder="Enter password" required>

                    <input type="submit" value="Login">

                </form>

            </div>
            <div id="error">
                <!-- form processing php here -->
                <p>Example error message</p>
            </div>
        </div>
    </div>
</body>

</html>
