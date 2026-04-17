<?php   
session_start();
if (!isset($_SESSION['user']) || $_SESSION['admin'] == 0) {
    header("Location: log_in_page.php");
    exit();
}

$userEmail = $_SESSION['user'];
$userName = $_SESSION['name'];
$message = "";

require_once 'connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_id']) && isset($_POST['description'])) {
    $slotId = (int)$_POST['slot_id'];
    $description = $_POST['description'];

    $sql = "UPDATE appointments SET description = ? WHERE slot_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$description, $slotId]);
    header("Location: upcoming_appointment.php");
    exit();
}
?>