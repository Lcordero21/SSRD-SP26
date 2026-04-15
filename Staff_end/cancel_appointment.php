
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['admin'] == 0) {
    header("Location: log_in_page.php");
    exit();
}

$userEmail = $_SESSION['user'];
$userName = $_SESSION['name'];
$slotId = $_GET['cancel'];
$userEmail = $_SESSION['student_email'];

$message = "";

require_once 'connect_db.php';

$sql = "DELETE FROM appointments WHERE slot_id = ? AND student_id = ?";
$pdo->prepare($sql);
$pdo ->execute([$slotId, $userEmail]);
$pdo->commit();
$message = "success";

header("Location: booking_page.php");
exit();
?>
