<?php
session_start();

// Redirect to Login.php if user is not a student
if ($_SESSION['role'] != 'student') {
    header("Location: Login.php");
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Fill In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg64y4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <form method="POST" action="generate.php">
            <h1>Generate Fillable PDF</h1>
            <p>Fill out the form and your data will fill in a PDF</p>
            <div class="form-group row">
                <div class="col-lg-6">
                    <input type="datetime-local" name="date" class="form-control" placeholder="Date for Week">
                </div>
                <div class="col-lg-6">
                    <input type="text" name="location" class="form-control" placeholder="Location">
                </div>
            </div>
            <div class="form-group">
                <textarea name="description" rows="10" class="form-control" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
                <input type="number" name="hours" class="form-control" placeholder="Hours">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-success">Submit Sheet</button>
            </div>
        </form>
    </div>
</body>
</html>
