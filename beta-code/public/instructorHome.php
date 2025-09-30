<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/home.css">
    <script>
        {
        const token = localStorage.getItem("jwt");

        fetch("api/decode.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    token
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('username').textContent = `${data.username}`;
                    document.getElementById('role').textContent = `${data.role}`;
                } else {
                    document.getElementById('role').textContent = data.message;
                }
            });
        }
    </script>

</head>

<body>
    <div class="navbar">
        <!-- Note that we dont include header.php as this home page is unique -->
        <div id="profile">
        </div>
        <h1>
            <div id="username"></div>
        </h1>
        <h1>
            <div id="role"></div>
        </h1>
    </div>

    <div class="maincontent">

        <div class="info_beside">
            <div class="desc_text">
                <h1>ABOUT</h1>
                <p>This is a website to analyse climbing, and comment on students videos.</p>
                <p>You can favorite videos and they will appear here on the home page too.</p>
                <p>You can also add sessions to your students calendars.</p>
            </div>

            <div class="links">
                <button class="button_links" onclick="window.location.href='createSession.php'">CREATE SESSION</button>
                <button class="button_links" onclick="window.location.href='studentList.php'">STUDENT LIST</button>
                <button class="button_links" onclick="window.location.href='addStudent.php'">ADD STUDENT</button>
                <button class="button_links" onclick="window.location.href='search.php'">SEARCH</button>
            </div>
        </div>


        <div class="liked_videos" style="display:flex; justify-content:center; align-items: center;">
        </div>
    </div>


    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
</body>

</html>
