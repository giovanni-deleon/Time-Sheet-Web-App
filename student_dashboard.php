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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg64y4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                <form method="POST" action="generate.php">
                    <h1>Fill Out Time Sheet</h1>
                    <p>Fill out the form to submit your time sheet</p>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <input type="datetime-local" name="date" class="form-control" placeholder="Date for Week">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" name="location" class="form-control" placeholder="Location">
                        </div>
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
