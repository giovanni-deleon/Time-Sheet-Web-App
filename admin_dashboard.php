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
    $timeEventID = $_POST['timeEventID'];
    $approveComment = $_POST['approveComment'] ?? ''; // Default to empty string if not set
    $approve = isset($_POST['approve']) ? 1 : 0; // 1 for approve, 0 for deny

    // Fetch the studentID and other details from event_time_sheet based on timeEventID
    $fetchDetailsQuery = "SELECT ID, date FROM event_time_sheet WHERE timeEventID = ?";
    $stmt = $con->prepare($fetchDetailsQuery);
    $stmt->bind_param("i", $timeEventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $studentID = $row['ID'];
        $studentSubmitDate = $row['date'];
        $approverID = $_SESSION['user_id']; // Assuming the admin's ID is stored in the session

        // Debugging: Output the values before inserting
        echo "Debugging: studentID=$studentID, approverID=$approverID, studentSubmitDate=$studentSubmitDate, approve=$approve, approveComment=$approveComment";

        // Insert data into timeSheet table
        $insertQuery = "INSERT INTO timeSheet (studentID, approverID, dateCreated, studentSubmitDate, approve, approveDate, approveComment) VALUES (?, ?, NOW(), ?, ?, NOW(), ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("iisis", $studentID, $approverID, $studentSubmitDate, $approve, $approveComment);

        if ($stmt->execute()) {
            echo "Time Event ID $timeEventID has been " . ($approve ? "approved" : "denied") . ".";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: Event details not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
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
                    <th>User Name</th>
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
                    <td><?php echo htmlspecialchars($row['userName']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="timeEventID" value="<?php echo htmlspecialchars($row['timeEventID']); ?>">
                            <textarea name="approveComment" class="form-control mb-2" placeholder="Comment (optional)"></textarea>
                            <button type="submit" name="approve" class="btn btn-success">Approve</button>
                            <button type="submit" name="deny" class="btn btn-danger">Deny</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
