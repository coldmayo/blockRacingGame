<?php 
session_start();
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
    if (isset($_COOKIE['highscore'])) {
        echo 'High score has been saved.';
    }
    $high = $_COOKIE['highscore'];
    $name = $user_data['user_name'];
    $query = "UPDATE users SET high_score = '$high' WHERE user_name = '$name'";
    mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Vroom</title>
<h1>Not Great Car Game That I Made in A Weekend</h1>
<link rel="stylesheet" type="text/css" href="gameCSS.css">
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<script type="text/javascript" src="gameJS.js"></script>
<button onClick="startGame();" class = "button">Play</button>
<button onClick="window.location.reload();" class="button1">Restart</button>
<a href="logout.php"><button class = "button2">Logout</button></a>
</body>
</html>
