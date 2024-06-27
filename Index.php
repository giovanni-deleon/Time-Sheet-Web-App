<?php
session_start();

    include("Connection.php");
    include("Functions.php");

    $user_data = check_login($con);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Original Website Name</title>
</head>
<body>

    <a href="Logout.php">Logout</a>
    <h1>This is the Index Page</h1>

    <br>
    Hello, <?php echo $user_data['user_name']; ?>

</body>
</html>