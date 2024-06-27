<?php
session_start();

    include("Connection.php");
    include("Functions.php");


    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        // Something was posted
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];


        if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){

            // Save to database
            $user_id = randum_num(20);
            $query = "Insert into users (user_id, user_name, password) values ('$user_id', '$user_name', '$password')"; 


            mysqli_query($con, $query);

            header("Location: Login.php");
            die;
        }else{
            echo "Please enter some valid information! ";
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

        paddding: 10px;
        width: 100px;
        color: white;
        background-color: Lightblue;
        border: none;
    }

    #box{

        background-color: grey;
        margin: auto;
        width: 300px;
        paddding: 20px;
    }

    </style>

    <div id="box">

        <form method="post">
            <div style="font-size: 20px;
                        margin: 10px; color: white;">Signup</div>

            <input id="text" type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="Login.php">Click to Login</a><br><br>
        </form>
    </div>
</body>
</html>