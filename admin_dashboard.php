<?php
session_start();
if($_SESSION['role'] != 'admin') {
    header("Location: Login.php");
    die;
}
//echo "Welcome, Admin " . $_SESSION['user_id'];
//?>
<a href="logout.php">Logout</a>
