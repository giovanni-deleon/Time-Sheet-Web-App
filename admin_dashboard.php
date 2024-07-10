<?php
session_start();

// Redirect to login if not logged in as admin
if ($_SESSION['role'] != 'admin') {
    header("Location: Login.php");
    die;
}

include("Connection.php");

// Fetch data from event_time_sheet table
$query = "SELECT * FROM event_time_sheet";
$result = mysqli_query($con, $query);

// Handle approval or denial
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['approve'])) {
        $timeEventID = $_POST['approve'];

        // Retrieve studentID (ID) from event_time_sheet
        $query = "SELECT ID FROM event_time_sheet WHERE timeEventID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $timeEventID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $studentID = $row['ID'];

        // Insert into timeSheet
        $approverID = $_SESSION['user_id']; // Assuming approverID is stored in session
        $dateCreated = date('Y-m-d H:i:s');
        $studentSubmitDate = $dateCreated; // Example: Same as dateCreated for now
        $approve = 1; // Example: Assuming approval is set as 1 for yes
        $approveDate = $dateCreated; // Example: Same as dateCreated for now
        $approveComment = "Approved"; // Example: A comment can be added here

        $insertQuery = "INSERT INTO timeSheet (studentID, approverID, dateCreated, studentSubmitDate, approve, approveDate, approveComment) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("iisssss", $studentID, $approverID, $dateCreated, $studentSubmitDate, $approve, $approveDate, $approveComment);
        
        if ($stmt->execute()) {
            echo "Time sheet approved successfully.";
            // Optionally, send an email or perform other actions upon approval
        } else {
            echo "Error approving time sheet: " . $stmt->error;
        }
    } elseif (isset($_POST['deny'])) {
        // Handle denial logic here if needed
        $timeEventID = $_POST['deny'];
        echo "Time Event ID $timeEventID denied.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg64y4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 50px;
        }
        .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_id']); ?></h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Time Event ID</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['timeEventID']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="approve" value="<?php echo htmlspecialchars($row['timeEventID']); ?>">
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form method="POST" action="">
                            <input type="hidden" name="deny" value="<?php echo htmlspecialchars($row['timeEventID']); ?>">
                            <button type="submit" class="btn btn-danger">Deny</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
