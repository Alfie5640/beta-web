<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Climber</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/addClimber.css">
    
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
        <!-- Include header.php later -->
        <div id="profile">
        </div>
        <h1>
            <div id="username"></div>
        </h1>
        <h1>
            <div id="role"></div>
        </h1>
        
        <?php
            include("instructorNav.php");
        ?>
    </div>
    
    <div class="maincontent">
        <div id="formBox">
            <form id="addForm" method="post">
                <div class="inputWrapper">
                    <p>Climber Username:</p>
                    <input id="climberUsername" type="text" placeholder="Enter username..." required>
                </div>
                
                <div class="submitWrapper">
                    <p></p>
                    <input type="submit" value="ADD" class="submitButton">
                </div>
                
            </form> 
        </div>
        <script src="js/addClimb.js"></script>
        <div id="error">
            <!-- form processing php here -->
        </div>
        
    </div>
    
    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
</body>
</html>
