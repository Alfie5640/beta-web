<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="styles/library.css">
    
    
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
        <!-- Include header.php later -->
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
        <div id="filter">
            <button class="narrowButton">FILTER</button>
            <button class="narrowButton">SEARCH</button>
        </div>
        
        <div id="videoLibrary">
        </div>
        
    </div>
    
    
    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
    
    <script src="js/libraryScript.js"></script>
</body>
</html>
