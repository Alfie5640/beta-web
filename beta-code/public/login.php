<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
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

                <form id="LoginForm" method="post">

                    <div class="inputWrapper">
                        <p>Username:</p>
                        <input id="username" type="text" placeholder="Enter username" required />
                    </div>

                    <div class="inputWrapper">
                        <p>Password:</p>
                        <input id="password" type="password" placeholder="Enter password" required>
                    </div>

                    <div class="submitWrapper">
                        <p></p>
                        <input type="submit" value="LOGIN" class="submitButton">
                    </div>
                </form>
                <div id="registerBox">
                    <a href="register.php">
                        <p id="register">Not registered? Create account here...</p>
                    </a>
                </div>
            </div>
        </div>
        <div id="error">
            <!-- form processing php here -->
            <script src="js/login.js"></script>
        </div>
        <div id="tokenDisplay">
        </div>
    </div>
</body>

</html>
