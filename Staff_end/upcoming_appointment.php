<!-- NEED TO ADD SESSION VERIFICATION -->
<?php?>

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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Booking Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="homepage.css">
</head>

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
          <a class="nav-link deactive" aria-current="page" href="#">Welcome <?= $userName ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Appointments
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="booking_page.php">Add New Appointment</a></li>
            <li><a class="dropdown-item" href="upcoming_appointment.php">View Upcoming Appointments</a></li>
            <li><a class="dropdown-item" href="search_appointment.php">Search for Appointment</a></li>
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

<body>
    <!-- looking at all upcoming appointments -->
    <?php
    $sql = "SELECT u.first, u.last, s.slot_date, a.staff_id, s.start_time, s.end_time, a.description, u2.first as staff_first, u2.last as staff_last FROM appointments a
            JOIN users u ON a.student_id = u.email
            JOIN slots s ON a.slot_id = s.id
            JOIN users u2 ON a.staff_id = u2.email
            WHERE s.slot_date >= CURDATE()
            ORDER BY s.slot_date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $upcomingAppointments = $stmt->fetchall();
    ?>
    <div class="container mt-4">
        <h2>Upcoming Appointments</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
              <th scope="col">Student Name</th>
              <th scope="col">Notes</th>
              <th scope="col">Edit Note</th>
              <th scope="col">Cancel</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($upcomingAppointments as $row) {
            ?>
            <tr>
              <td><?= htmlspecialchars(date('l, F j Y', strtotime($row['slot_date']))) ?></td>
              <td><?=date('g:ia', strtotime($row['start_time'])) ?> - <?= date('g:ia', strtotime($row['end_time'])) ?></td>
              <td><?= htmlspecialchars($row['first']) ?> <?= htmlspecialchars($row['last']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <!-- Fix the following -->
              <td><a href="adjust_appointment.php?slot_id=<?= $row['slot_id'] ?>" class="btn btn-outline-primary">Edit Note</a></td>
              <td><a href="cancel_appointment.php?slot_id=<?= $row['slot_id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Cancel this appointment?')">Cancel</a></td>

            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div> 
</body>
