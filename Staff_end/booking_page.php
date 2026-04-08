<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../log_in_page.php");
    exit();
}
require_once '../connect_db.php';

$userEmail = $_SESSION['user'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_id'])) {
    $slotId = (int)$_POST['slot_id'];

    // Double checks slot is still available
    $check = $pdo->prepare("SELECT * FROM slots WHERE id = ? AND is_booked = 0");  // Fixed: Use $pdo
    $check->execute([$slotId]);
    $slot = $check->fetch(PDO::FETCH_ASSOC);

    // Check user doesn't already have a future booking
    $existing = $pdo->prepare(
        "SELECT a.id FROM appointments a
        JOIN slots s ON a.slot_id = s.id
        WHERE a.user_email = ? AND s.slot_date >= CURDATE()
        "
    );
    $existing->execute([$userEmail]);
    $hasBooking = $existing->fetch();

    if (!$slot) {
        $message = "Sorry, that slot was just taken. Please choose another.";
    } elseif ($hasBooking) {
        $message = "You already have an upcoming appointment. Call to reschedule if you need to change it.";
    } else {
        $pdo->beginTransaction();
        try {
            $pdo->prepare("INSERT INTO appointments (slot_id, user_email) VALUES (?, ?)")
                ->execute([$slotId, $userEmail]);
            $pdo->prepare("UPDATE slots SET is_booked = 1 WHERE id = ?")
                ->execute([$slotId]);
            $pdo->commit();
            $message = "success";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Something got funky. Please try again!";
        }
    }
}

// Cancels an appointment
if (isset($_GET['cancel'])) {
    $slotId = (int)$_GET['cancel'];
    $pdo->prepare("DELETE FROM appointments WHERE slot_id = ? AND user_email = ?")
        ->execute([$slotId, $userEmail]);
    $pdo->prepare("UPDATE slots SET is_booked = 0 WHERE id = ?")
        ->execute([$slotId]);
    header("Location: booking_page.php?cancelled=true");  // Fixed: Stay on same page
    exit();
}

// Gets current user's upcoming appointment
$myBooking = $pdo->prepare(
    "SELECT a.*, s.slot_date, s.start_time, s.end_time 
    FROM appointments AS a
    JOIN slots AS s ON a.slot_id = s.id
    WHERE a.user_email = ? AND s.slot_date >= CURDATE()
");
$myBooking->execute([$userEmail]);
$myBooking = $myBooking->fetch(PDO::FETCH_ASSOC);

// Get all available appointments
$slots = $pdo->prepare("
    SELECT * FROM slots 
    WHERE is_booked = 0 AND slot_date >= CURDATE()
    ORDER BY slot_date, start_time
");
$slots->execute();
$allSlots = $slots->fetchAll(PDO::FETCH_ASSOC);

// Group by date
$apptsByDate = [];
foreach ($allSlots as $slot) {
    $apptsByDate[$slot['slot_date']][] = $slot;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Book an Appointment</h2>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert alert-info">Your appointment has been cancelled.</div>
    <?php endif; ?>

    <?php if ($message === "success"): ?>
        <div class="alert alert-success">Appointment booked successfully!</div>
    <?php elseif ($message !== ""): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Show existing booking -->
    <?php if ($myBooking): ?>
        <div class="card mb-4 border-primary">
            <div class="card-body">
                <h5 class="card-title">Your Upcoming Appointment</h5>
                <p>
                    <strong>Date:</strong> <?= date('l, F j Y', strtotime($myBooking['slot_date'])) ?><br>
                    <strong>Time:</strong> <?= date('g:ia', strtotime($myBooking['start_time'])) ?> 
                                         - <?= date('g:ia', strtotime($myBooking['end_time'])) ?>
                </p>
                <a href="?cancel=<?= $myBooking['slot_id'] ?>" 
                   class="btn btn-outline-danger"
                   onclick="return confirm('Cancel this appointment?')">
                    Cancel Appointment
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Available slots -->
    <?php if (!$myBooking): ?>
        <?php if (empty($apptsByDate)): ?>
            <div class="alert alert-warning">No available appointments at this time.</div>
        <?php else: ?>
            <?php foreach ($apptsByDate as $date => $slots): ?>
                <h5 class="mt-4"><?= date('l, F j Y', strtotime($date)) ?></h5>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <?php foreach ($slots as $slot): ?>
                        <form method="POST">
                            <input type="hidden" name="slot_id" value="<?= $slot['id'] ?>">
                            <button type="submit" class="btn btn-outline-primary">
                                <?= date('g:ia', strtotime($slot['start_time'])) ?> 
                                - <?= date('g:ia', strtotime($slot['end_time'])) ?>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-muted">Cancel your current appointment to book a different time.</p>
    <?php endif; ?>
</div>
</body>
</html>