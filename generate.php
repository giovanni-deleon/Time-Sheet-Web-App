<?php
session_start();

include("Connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $date = $_POST['date'];
    $location = $_POST['location'];
    $weekDate = $_POST['weekDate'];
    $description = $_POST['description'];
    $hours = $_POST['hours'];
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    if (!empty($date) && !empty($location) && !empty($weekDate) && !empty($description) && !empty($hours) && !empty($user_name)) {
        // Insert into event_time_sheet table
        $query = "INSERT INTO event_time_sheet (ID, userName, date, location, weekDate, description, hours) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("isssssi", $user_id, $user_name, $date, $location, $weekDate, $description, $hours);
        
        if ($stmt->execute()) {
            echo "Time sheet submitted successfully.";
            header("Location: student_dashboard.php");
            die;
        } else {
            echo "Error submitting time sheet: " . $stmt->error;
        }
    } else {
        echo "Please fill out all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate</title>
</head>
<body>
    <h1>Generating Time Sheet</h1>
</body>
</html>
