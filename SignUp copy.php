<?php
session_start();

include("Connection.php");
include("Functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the role from the form

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Save to database
        //$user_id = random_num(20);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (user_name, password, role) VALUES ('$user_name', '$hashed_password', '$role')"; 

        mysqli_query($con, $query);

        header("Location: Login.php");
        die;
    } else {
        echo "Please enter some valid information!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
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
        <div style="font-size: 20px; margin: 10px; color: white;">Signup</div>

        <input id="text" type="text" name="user_name" placeholder="Username"><br><br>
        <input id="text" type="password" name="password" placeholder="Password"><br><br>
        <select id="text" name="role">
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </select><br><br>
        <input id="button" type="submit" value="Signup"><br><br>

        <a href="Login.php">Click to Login</a><br><br>
    </form>
</div>
</body>
</html>
