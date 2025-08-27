<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        #footer {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 2px;
        }

        #logout_button {
            color: white;
            background-color: #D90166;
            border: 0.5px solid black;
            font-family: "Bebas Neue", sans-serif;
            font-size: 25px;
            border-radius: 8px;
            margin-right: 20px;
            height: 75%;
            width: 8%;
        }

        #logout_button:hover {
            background-color: #E11584;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <div id="footer">
        <button id="logout_button" onclick="window.location.href = 'login.php'">LOGOUT</button>
    </div>
</body>

</html>
