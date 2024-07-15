<?php
session_start();

// Redirect to Login.php if user is not a student
if ($_SESSION['role'] != 'student') {
    header("Location: Login.php");
    die;
}

include("Connection.php");

// Fetch approved time sheets for the student
$studentID = $_SESSION['user_id'];
$query = "SELECT * FROM timeSheet WHERE studentID = ? AND approve = 1";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $hours = $_POST['hours'] ?? '';
    $weekDate = $_POST['weekDate'] ?? '';
    $dateCreated = date('Y-m-d H:i:s'); // Current date and time

    // Validate form fields
    if (empty($location) || empty($description) || empty($hours) || empty($weekDate)) {
        $error = "Please fill out all fields.";
    } else {
        // Process form submission
        $stmt = $con->prepare("INSERT INTO event_time_sheet (ID, location, description, hours, weekDate, date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $studentID, $location, $description, $hours, $weekDate, $dateCreated);

        if ($stmt->execute()) {
            header("Location: student_dashboard.php");
            die;
        } else {
            $error = "Error submitting time sheet: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg64y4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
        
        .container {
            margin-top: 50px;
            max-width: 800px;
            width: 100%;
        }
        
        .btn {
            margin-right: 10px;
        }
        
        .form-control {
            border-radius: 4px;
            box-shadow: none;
            border-color: #ccc;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.1);
        }
        
        .btn-block {
            border-radius: 4px;
        }
        
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        h1 {
            font-weight: 700;
            color: #333;
        }
        
        p {
            color: #666;
        }
        
        select {
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        
        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                <form method="POST" action="">
                    <h1>Fill Out Time Sheet</h1>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <p>Fill out the form to submit your time sheet</p>
                    <div class="form-group">
                        <input type="text" name="location" class="form-control" placeholder="Location">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="5" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="number" name="hours" class="form-control" placeholder="Hours">
                    </div>
                    <div class="form-group">
                        <input type="text" name="weekDate" class="form-control" placeholder="Week Date (e.g., June 6 - June 7)">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">Submit Time Sheet</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h1>Print Approved Time Sheet</h1>
                <p>Select a time sheet to print as a PDF.</p>
                <form method="POST" action="print_pdf.php">
                    <div class="form-group">
                        <select name="timeSheetID" class="form-control">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['timeSheetID']); ?>">
                                    <?php echo htmlspecialchars($row['weekDate']) . " - " . htmlspecialchars($row['hours']) . " hours"; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary">Print PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
