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
        <li onclick="window.location.href='createSession.php'">Create Session</li>
        <li onclick="window.location.href='search.php'">Search</li>
        <li onclick="window.location.href='studentList.php'">Student List</li>
        <li onclick="window.location.href='addStudent.php'">Add Student</li>
        <li onclick="window.location.href='instructorHome.php'">Home</li>
    </ul>
</body>

</html>
