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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    </style>
</head>
<body>
    <div id="box">
        <form method="post">
            <h2>Login</h2>
            <input id="text" type="text" name="user_name" placeholder="Username"><br>
            <input id="text" type="password" name="password" placeholder="Password"><br>
            <input id="button" type="submit" value="Login"><br>
            <a href="Signup.php">Click to Signup</a>
        </form>
    </div>
</body>
</html>
