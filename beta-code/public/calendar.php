<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link href="styles/calendar.css" rel="stylesheet">

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
        <div class="calendarElement">
            <button id="addEvent" onclick="showEventForm()">Add Event</button>
        </div>

        <div id="hiddenForm" class="calendarElement">
            <div class="formRow">
                <label>Date:</label>
                <input type="date" id="eventDate">
            </div>
            <div class="formRow">
                <label>Start Time:</label>
                <input type="time" id="eventStart">
            </div>
            <div class="formRow">
                <label>End Time:</label>
                <input type="time" id="eventEnd">
            </div>
            <div class="formRow">
                <label>Title:</label>
                <input type="text" id="eventTitle" placeholder="Event title">
            </div>
            <button id="saveEvent" onclick="submitEvent()">Save</button>
        </div>
    </div>

    <div class="footer">
        <?php
        include("footer.php");
            ?>
    </div>

    <script src="js/calendar.js"></script>
</body>

</html>
