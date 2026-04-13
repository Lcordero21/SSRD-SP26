<!-- NEED TO ADD SESSION VERIFICATION -->
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    $sql = "SELECT u.first, u.last, s.slot_date, a.staff_id, s.start_time, s.end_time, a.description, u2.first as staff_first, u2.last as staff_last FROM appointments a
            JOIN users u ON a.student_id = u.email
            JOIN slots s ON a.slot_id = s.id
            JOIN users u2 ON a.staff_id = u2.email
            WHERE u.first= ? AND u.last = ?
            ORDER BY s.slot_date DESC";
    $existing = $pdo->prepare($sql);
    $existing->execute([$firstName, $lastName]);
    $hasBooking = $existing->fetchall();
}
?>



<!-- Might drop this page...if not then it will search through past appointments -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Booking Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="homepage.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
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
    <!-- looking at all upcoming appointments and adjusting them here -->

  <form method="POST" action="search_appointment.php">
    <div class="mb-3">
      <label for="first_name" class="form-label">Student First Name</label>
      <input type="text" class="form-control" id="first_name" name="first_name" required>
      <label for="last_name" class="form-label">Student Last Name</label>
      <input type="text" class="form-control" id="last_name" name="last_name" required>
      <div id="slotHelp" class="form-text">Search for appointments by student first and last name.</div>
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
  </form>


  <?php if (isset($hasBooking) && !$hasBooking): ?>
    <div class="alert alert-danger mt-3" role="alert">
      No appointments found for <?= htmlspecialchars($firstName) ?> <?= htmlspecialchars($lastName) ?>.
    </div>
  <?php endif; ?>

  <?php if (isset($hasBooking) && $hasBooking): ?>
    <div class="alert alert-success mt-3" role="alert">
        Appointment(s) found for <?= htmlspecialchars($hasBooking[0]['first']) ?> <?= htmlspecialchars($hasBooking[0]['last']) ?>.<br> 

    </div>
      <div class="container mt-4">
      <div class = "container">
      <table class="table">
  <thead class = "table-striped">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Staff Name</th>
      <th scope="col">Notes</th>
    </tr>
  </thead>
  <tbody class = "table-group-divider">
    <?php
      foreach ($hasBooking as $row) {
    ?>
    
    <tr>
      <td><?= date('l, F j Y', strtotime($row['slot_date']))?></td>
      <td><?=date('g:ia', strtotime($row['start_time'])) ?> 
            - <?= date('g:ia', strtotime($row['end_time'])) ?></td>
      <td><?= htmlspecialchars($row['staff_first']) ?> <?= htmlspecialchars($row['staff_last']) ?></td> 
      <td><?= htmlspecialchars($row['description']) ?></td>
    </tr>
    <?php
      }
    ?>
  <?endforeach; ?>
    <?php endif; ?>

  </tbody>
  </table>
    </div>

</body>

<footer>
  <p> </p>
</footer>

</html>