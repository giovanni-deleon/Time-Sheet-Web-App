<?php
session_start();





include("Connection.php");
include("Functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Read from database
        $query = "SELECT * FROM users WHERE user_name = ? LIMIT 1";

        // Prepare and bind
        if ($stmt = mysqli_prepare($con, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $user_name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if(password_verify($password, $user_data['password'])) {
                    //$_SESSION['user_id'] = $user_data['user_id'];
                    $_SESSION['role'] = $user_data['role'];

                    if($user_data['role'] == 'admin') {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: student_dashboard.php");
                    }
                    die;
                }
            }
        }
        echo "Wrong username or password!";
    } else {
        echo "Please enter some valid information!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<style type="text/css">
    #text{
        height: 25px;
        border-radius: 5px;
        padding: 4px;
        border: solid thin #aaa;
        width: 90%;
    }

    #button{
        padding: 10px;
        width: 100px;
        color: white;
        background-color: lightblue;
        border: none;
    }

    #box{
        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
    }
</style>

<div id="box">
    <form method="post">
        <div style="font-size: 20px; margin: 10px; color: white;">Login</div>

        <input id="text" type="text" name="user_name" placeholder="Username"><br><br>
        <input id="text" type="password" name="password" placeholder="Password"><br><br>

        <input id="button" type="submit" value="Login"><br><br>

        <a href="Signup.php">Click to Signup</a><br><br>
    </form>
</div>
</body>
</html>
