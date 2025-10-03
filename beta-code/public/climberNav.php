<!DOCTYPE html>
<html>

<head>
    <style>
    
        ul {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            text-decoration: underline;
            text-decoration-color:#D90166;
            list-style-type: none;            
        }
        
        li {
            cursor: pointer;
            font-family: "Bebas Neue", sans-serif;
            font-size: 25px;
        }
        
        ul li {
            margin: 0 10px;
        }
    
    </style>
</head>

<body>
    <script src=""></script>
    <ul>
        <li onclick="window.location.href='library.php'">Library</li>
        <li onclick="window.location.href='search.php'">Search</li>
        <li onclick="window.location.href='calendar.php'">Calendar</li>
        <li onclick="window.location.href='upload.php'">Upload</li>
        <li onclick="window.location.href='climberHome.php'">Home</li>
    </ul>
</body>

</html>
