<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClimberHome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/home.css">
    
    <script src="js/profileInfo.js"></script>
    
</head>

<body>
    <div class="navbar">
        <!-- Note that we dont include header.php as this home page is unique -->
        <div id="profile">
        </div>
        <h1>Username</h1>
        <h1>Climber</h1>
    </div>


    <div class="maincontent">

        <div class="info_beside">
            <div class="desc_text">
                <h1>ABOUT</h1>
                <p>This is a website to store your climbing videos, and have friends / 'instructors' watch and comment on them.</p>
                <p>You can favorite videos and they will appear here on the home page too.</p>
                <p>You can also manage your climbing schedule with the built in calendar.</p>
            </div>

            <div class="links">
                <button class="button_links" onclick="window.location.href='upload.php'">UPLOAD</button>
                <button class="button_links" onclick="window.location.href='calendar.php'">CALENDAR</button>
                <button class="button_links" onclick="window.location.href='library.php'">LIBRARY</button>
            </div>
        </div>
        
        
        <div class="liked_videos" style="display:flex; justify-content:center; align-items: center;">
            <h1>FAVOURITED VIDEOS</h1>
        </div>
    </div>


    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
</body>

</html>
