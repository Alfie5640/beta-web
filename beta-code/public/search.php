<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="styles/search.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script>
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
        
        <?php
            include("climberNav.php");
        ?>
    </div>

    <div class="maincontent">
        
        <form id="searchForm" method="post">
            <!-- If role == instructor then add climber element !-->
            <h1>Climber</h1>
            <input type="text" name="climberName" required>
            
            <h1>Title</h1>
            <input type="text" name="videoTitle" required>
        
            <input type="submit" value="Upload" id="submit">
        </form>
    </div>
    
    
    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
</body>
</html>
