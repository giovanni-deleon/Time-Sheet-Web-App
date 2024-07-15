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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f6fa;
            font-family: 'Roboto', sans-serif;
            margin: 0;
        }

        #box {
            background: #fff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        #box h2 {
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
        }

        #text {
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        #text:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.1);
        }

        #button {
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 14px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            transition: background-color 0.3s;
        }

        #button:hover {
            background-color: #2980b9;
        }

        a {
            color: #3498db;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: color 0.3s;
        }

        a:hover {
            color: #2980b9;
        }

        select {
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.1);
        }
    </style>
</head>
<body>
    <div id="box">
        <form method="post">
            <h2>Signup</h2>
            <input id="text" type="text" name="user_name" placeholder="Username"><br>
            <input id="text" type="password" name="password" placeholder="Password"><br>
            <select id="text" name="role">
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select><br>
            <input id="button" type="submit" value="Signup"><br>
            <a href="Login.php">Click to Login</a>
        </form>
    </div>
</body>
</html>
