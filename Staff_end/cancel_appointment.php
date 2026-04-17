
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['admin'] == 0) {
    header("Location: log_in_page.php");
    exit();
}


// All of the following will cancel an appointment by removing it from the appointments table and setting the slot to not booked. It then redirects back to the upcoming appointments page. 
// This was fixed by copilot to stay on the same page instead of going to homepage.
if (!isset($_POST['slot_id'])) {
    header("Location: upcoming_appointment.php");
    exit();
}


$userEmail = $_SESSION['user'];
$userName = $_SESSION['name'];
$slotId = $_POST['slot_id'];
$studentId = $_SESSION['student_id'];

require_once 'connect_db.php';

$sql = "DELETE FROM appointments WHERE slot_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$slotId]);

$sql = "UPDATE slots SET is_booked = 0 WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$slotId]);

header("Location: upcoming_appointment.php");
exit();
?>
