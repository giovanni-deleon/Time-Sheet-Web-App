<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: Login.php");
    die;
}

include("Connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $hours = $_POST['hours'];

//////////////////////////////////////////////////////////////////////////

    $userID = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    //////////////////////////////////////////////////////////////////////////


    $query = "INSERT INTO event_time_sheet (date, location, description, userName, ID) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($query);
$stmt->bind_param("ssssi", $date, $location, $description, $username, $userID);


    // Insert data into event_time_sheet table
   // $query = "INSERT INTO event_time_sheet (date, location, description, hours) VALUES (?, ?, ?, ?)";
   // $stmt = $con->prepare($query);
    //$stmt->bind_param("ssss", $date, $location, $description, $hours);

    if ($stmt->execute()) {
        // Get the ID of the inserted row
        $timeEventID = $stmt->insert_id;

        // Redirect to display.php with the inserted data
        $_SESSION['submitted_data'] = [
            'timeEventID' => $timeEventID,
            'date' => $date,
            'location' => $location,
            'description' => $description, 
            'hours' => $hours
        ];
        header("Location: display.php");
        die;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: student_dashboard.php");
    die;
}
?>
