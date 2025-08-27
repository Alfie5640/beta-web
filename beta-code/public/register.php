<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
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
                        <input type="submit" value="REGISTER" class="submitButton">
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
