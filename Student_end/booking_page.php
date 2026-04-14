<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['admin'] == 1) { 
    header("Location: log_in_page.php");
    exit();
}
require_once 'connect_db.php';

$userEmail = $_SESSION['user'];
$userName = $_SESSION['name'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_id'])) {
    $slotId = (int)$_POST['slot_id'];

    // double checks slot is still available (per the suggestion of claude)
    $sql = "SELECT * FROM slots WHERE id = ? AND is_booked = 0";
    $check = $pdo->prepare($sql);
    $check->execute([$slotId]);
    $slot = $check->fetch(PDO::FETCH_ASSOC);

    // checks that the student doesn't already have a future booking

    $sql = "SELECT a.id FROM appointments a
            JOIN slots s ON a.slot_id = s.id
            WHERE a.student_id = ? AND s.slot_date >= CURDATE()";
    $existing = $pdo->prepare($sql);
    $existing->execute([$userEmail]);
    $hasBooking = $existing->fetch();

    if (!$slot) {
        $message = "Sorry, that slot was taken. Please choose another.";
    } elseif ($hasBooking) {
        $message = "You already have an upcoming appointment. Cancel your current one or call to reschedule if you need to change it.";
    } else {

    //suggested by claude to double check the slot is still available right before booking  
        $pdo->beginTransaction();
        $sql = "SELECT staff_id FROM slots WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$slotId]);
        $slotData = $stmt->fetch(PDO::FETCH_ASSOC);
        try {
            $sql = "INSERT INTO appointments (slot_id, student_id, staff_id, booked) VALUES (?, ?, ?, NOW())";
            $pdo->prepare($sql)
                ->execute([$slotId, $userEmail, $slotData['staff_id']]);
            $sql = "UPDATE slots SET is_booked = 1 WHERE id = ?";
            $pdo->prepare($sql)
                 ->execute([$slotId]);
            $pdo->commit();
            $message = "success";
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Something got funky. Please try again!";
        }
    }
}

//for cancelling an appointment
if (isset($_GET['cancel'])) {
    $slotId = (int)$_GET['cancel'];
    $pdo->beginTransaction();
    try {
        $pdo->prepare("DELETE FROM appointments WHERE slot_id = ? AND student_id = ?")
            ->execute([$slotId, $userEmail]);
        $pdo->prepare("UPDATE slots SET is_booked = 0 WHERE id = ?")
            ->execute([$slotId]);
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
    header("Location: booking_page.php?cancelled=true");  // line was fixed by claude to stay on same page instead of going to homepage
    exit();
}


//get the upcoming appointment for the user
$sql = "SELECT a.*, s.slot_date, s.start_time, s.end_time 
        FROM appointments AS a
        JOIN slots AS s ON a.slot_id = s.id
        WHERE a.student_id = ? AND s.slot_date >= CURDATE()
        ORDER BY s.slot_date, s.start_time";
$myBooking = $pdo->prepare($sql);
$myBooking->execute([$userEmail]);
$myBooking = $myBooking->fetch(PDO::FETCH_ASSOC);

//get all available appts.
$sql = "SELECT * FROM slots 
        WHERE is_booked = 0 AND slot_date >= CURDATE()
        ORDER BY slot_date, start_time";
$slots = $pdo->prepare($sql);
$slots->execute();
$allSlots = $slots->fetchAll(PDO::FETCH_ASSOC);

//just organizes the appts by date (per the suggestion of claude)
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="booking_page.css">
</head>

<body>
<header>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img class = "compass_logo" src="../images/compass-4c1.jpg"/></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link deactive" aria-current="page" id = "welcome" href="#">Welcome <?= $userName ?>!</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Appointments
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="booking_page.php">Book an Appointment</a></li>
            <li><a class="dropdown-item" href="past_appointments.php">Past Appointments</a></li>
            <li><a class="dropdown-item" href="upcoming_appointment.php">Upcoming Appointments</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logging_out.php">Log Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
</header>



<div class="container mt-4">
    <h2>Book an Appointment</h2>

    <?php if (isset($_GET['cancelled'])): ?>
        <div class="alert alert-success">Your appointment has been cancelled.</div>
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
                 <!-- following was suggested by claude to make the date and time format more user friendly. I also added a border/card around the existing booking  -->
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
                            <button type="submit" class="btn btn-danger">
                                <?= date('g:ia', strtotime($slot['start_time'])) ?> 
                                - <?= date('g:ia', strtotime($slot['end_time'])) ?>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <p class="text">Cancel your current appointment to book a different time.</p>
    <?php endif; ?>
</div>
</body>
</html>