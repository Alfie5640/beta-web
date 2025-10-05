<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <title>Video</title>
    <link rel="stylesheet" href="styles/viewVid.css">


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

                        if (data.role == "climber") {
                            document.getElementById("climberNav").style.display = "block";
                            document.getElementById("instructorNav").style.display = "none";
                        } else {
                            document.getElementById("climberNav").style.display = "none";
                            document.getElementById("instructorNav").style.display = "block";
                        }

                    } else {
                        document.getElementById('role').textContent = data.message;
                    }
                });
        }

    </script>



</head>

<body>


    <script src=""></script>
    <div class="navbar">
        <div id="profile">
        </div>
        <h1>
            <div id="username"></div>
        </h1>
        <h1>
            <div id="role"></div>
        </h1>

        <div id="climberNav" style="display:none;">
            <?php include("climberNav.php"); ?>
        </div>
        <div id="instructorNav" style="display:none;">
            <?php include("instructorNav.php"); ?>
        </div>
    </div>

    <div class="maincontent">

        <div id="selectedVid">
        </div>

        <div id="videoFormDetails">

            <div class="labelSelection">

                <div class="label-option" draggable = true>
                    <h1>LEFT HAND</h1>
                </div>
                <div class="label-option" draggable = true>
                    <h1>RIGHT HAND</h1>
                </div>

            </div>
            <div class="labelSelection">
                <div class="label-option" draggable = true>
                    <h1>LEFT FOOT</h1>
                </div>
                <div class="label-option" draggable = true>
                    <h1>RIGHT FOOT</h1>
                </div>



            </div>
            <div class="labelSelection">
                <div class="label-option" draggable = true>
                    <h1>FLAG</h1>
                </div>
                <div class="label-option" draggable = true>
                    <h1>START</h1>
                </div>


            </div>
            <div class="labelSelection">
                <div class="label-option" draggable = true>
                    <h1>TOP</h1>
                </div>
                <div class="label-option" draggable = true>
                    <h1>CUSTOM</h1>
                </div>

            </div>
            <div id="comSub">
                <input type="textarea" id="commentArea" />
                <input type="submit" id="submitButton">
            </div>
        </div>


    </div>



    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>

    <script src="js/viewVid.js"></script>
</body>

</html>
