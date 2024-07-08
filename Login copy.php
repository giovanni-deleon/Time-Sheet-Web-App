<?php
session_start();

include("Connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password)) {
        // Read from the database
        $query = "SELECT * FROM users WHERE user_name = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            
            // Verify password
            if(password_verify($password, $user_data['password'])) {
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['role'] = $user_data['role'];
                $_SESSION['username'] = $user_data['user_name'];

                if ($user_data['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                die;
            }
        }
        
        echo "Invalid username or password!";
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
