<?php 
session_start();
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
?>
<!DOCTYPE html>
<html>
<head>
<title>Benus</title>
<h1>Not Great Car Game That I Made in A Weekend</h1>
</head>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
canvas {
    border:1px solid #d3d3d3;
    background-color: #f1f1f1;
}
</style>
<script type="text/javascript" src="gameJS.js"></script>  
<button onClick="startGame();">Play</button>
<button onClick="window.location.reload();">Restart</button>
<a href="logout.php"><button>Logout</button></a>
</body>
</html>
