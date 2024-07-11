<?php
session_start();

// Redirect to Login.php if user is not a student
if ($_SESSION['role'] != 'student') {
    header("Location: Login.php");
    die;
}

include("Connection.php");
require 'vendor/autoload.php';

use Dompdf\Dompdf;

// Retrieve studentID from session
$studentID = $_SESSION['user_id'];

// Get selected time sheet ID from POST request
$timeSheetID = $_POST['timeSheetID'];

// Fetch the selected approved time sheet data for the student
$query = "SELECT * FROM timeSheet WHERE studentID = ? AND timeSheetID = ? AND approve = 1";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $studentID, $timeSheetID);
$stmt->execute();
$result = $stmt->get_result();

// Check if the approved time sheet exists
if ($result->num_rows == 0) {
    echo "No approved time sheet found.";
    die;
}

// Create a new instance of Dompdf
$dompdf = new Dompdf();

$row = $result->fetch_assoc();
$html = '<h1>Approved Time Sheet</h1>';
$html .= '<p><strong>Student ID:</strong> ' . htmlspecialchars($row['studentID']) . '</p>';
$html .= '<p><strong>Date Created:</strong> ' . htmlspecialchars($row['dateCreated']) . '</p>';
$html .= '<p><strong>Student Submit Date:</strong> ' . htmlspecialchars($row['studentSubmitDate']) . '</p>';
$html .= '<p><strong>Approver ID:</strong> ' . htmlspecialchars($row['approverID']) . '</p>';
$html .= '<p><strong>Approve Date:</strong> ' . htmlspecialchars($row['approveDate']) . '</p>';
$html .= '<p><strong>Approve Comment:</strong> ' . htmlspecialchars($row['approveComment']) . '</p>';
$html .= '<p><strong>Hours:</strong> ' . htmlspecialchars($row['hours']) . '</p>';
$html .= '<p><strong>Week Date:</strong> ' . htmlspecialchars($row['weekDate']) . '</p>';

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("approved_time_sheet", array("Attachment" => 0));
?>
