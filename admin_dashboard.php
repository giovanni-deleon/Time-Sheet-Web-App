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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            max-width: 900px;
            width: 100%;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .btn {
            margin-right: 10px;
        }
        
        .table thead th {
            border-bottom: 2px solid #dee2e6;
        }
        
        .btn {
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        
        .btn-success:hover {
            background-color: #218838;
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
        
        select, input {
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        select:focus, input:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.1);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".approve-btn").click(function(e) {
                e.preventDefault();
                var timeEventID = $(this).val();
                var row = $(this).closest("tr");
                $.ajax({
                    url: "approve_deny.php",
                    type: "POST",
                    data: {approve: timeEventID},
                    success: function(response) {
                        if(response == "success") {
                            row.remove();
                        } else {
                            alert("Error approving time sheet.");
                        }
                    }
                });
            });

            $(".deny-btn").click(function(e) {
                e.preventDefault();
                var timeEventID = $(this).val();
                var row = $(this).closest("tr");
                $.ajax({
                    url: "approve_deny.php",
                    type: "POST",
                    data: {deny: timeEventID},
                    success: function(response) {
                        if(response == "success") {
                            row.remove();
                        } else {
                            alert("Error denying time sheet.");
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user_id']); ?></h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Time Event ID</th>
                    <th>Student ID</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Hours</th>
                    <th>Week Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['timeEventID']); ?></td>
                    <td><?php echo htmlspecialchars($row['ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                    <td><?php echo htmlspecialchars($row['hours']); ?></td>
                    <td><?php echo htmlspecialchars($row['weekDate']); ?></td>
                    <td>
                        <button class="btn btn-success approve-btn" value="<?php echo htmlspecialchars($row['timeEventID']); ?>">Approve</button>
                        <button class="btn btn-danger deny-btn" value="<?php echo htmlspecialchars($row['timeEventID']); ?>">Deny</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
