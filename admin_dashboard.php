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
        // Handle approve logic
        $timeEventID = $_POST['approve'];
        // Implement approve functionality as needed
        echo "Approved Time Event ID: $timeEventID";
    } elseif (isset($_POST['deny'])) {
        // Handle deny logic
        $timeEventID = $_POST['deny'];
        // Implement deny functionality as needed
        echo "Denied Time Event ID: $timeEventID";
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
