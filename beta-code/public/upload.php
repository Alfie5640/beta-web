<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/upload.css">

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

        <div id="uploadVid">

            <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <h1>Select video to upload:</h1>

                <input type="file" name="fileToUpload" id="fileToUpload" accept="video/*">
                <label for="fileToUpload" class="custom-file-upload">Choose Video</label>
                
                <video id="videoPreview" width="320" height="240" controls style="display:none;"></video>

                <br><br>
                
                <input type="submit" value="Upload" name="submit">
            </form>

        </div>

        <div id="uploadDetails">

            <form>
            </form>

        </div>

    </div>


    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>
    
    <script src="js/upload.js"></script>
</body>

</html>
