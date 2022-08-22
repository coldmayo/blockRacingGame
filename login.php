<?php 
session_start();
include("connection.php");
include("functions.php");

if($_SERVER['REQUEST_METHOD']=="POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password)) {
        $query = "select * from users where user_name = '$user_name' limit 1";
        
        $result = mysqli_query($con, $query);
        if ($result) {
            if($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                
                if ($user_data['password'] === $password) {
                    $_SESSION['user_id'] = $user_data['user_id'];
                    header("Location: gameAfterLogin.php");
                    die;
                }
            }
        }
        echo("Please enter the right login information, I dont have all day");
    }
    else {
        echo("Please enter the right login information, I dont have all day");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <style type ="text/css">

        </style>
        <div id = "box">
            <form method="post">
                <input id = "text" type = "text" name = "user_name"><br><br>
                <input id = "text" type = "password" name = "password"><br><br>
                <input id = "button" type = "submit" value = "Login"><br><br>
                <a href = "signup.php">Click to Sign up</a><br><br>
            </form>
        </div>
    </body>
</html>