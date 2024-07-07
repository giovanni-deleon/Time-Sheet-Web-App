<?php
include("Connection.php");

 SQL command to add the role column
$query = "ALTER TABLE users ADD COLUMN role ENUM('admin', 'student') NOT NULL DEFAULT 'student'";

if (mysqli_query($con, $query)) {
    echo "Column added successfully";
} else {
    echo "Error adding column: " . mysqli_error($con);
}
?>
