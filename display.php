<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: Login.php");
    die;
}

if (!isset($_SESSION['submitted_data'])) {
    header("Location: student_dashboard.php");
    die;
}

$data = $_SESSION['submitted_data'];
unset($_SESSION['submitted_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <h1>Data Submitted Successfully</h1>
        <p><strong>Time Event ID:</strong> <?php echo htmlspecialchars($data['timeEventID']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($data['date']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($data['location']); ?></p>
        <p><strong>Hours:</strong> <?php echo htmlspecialchars($data['hours']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
        <a href="student_dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
