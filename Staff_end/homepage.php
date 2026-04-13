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

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Main Page</title>
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
            <li><a class="dropdown-item" href="booking_page.php">Book New Appointment</a></li>
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
<!-- The main navigation tiles-->
  <div class="row">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Book an Appointment</h5>
            <p class="card-text">Look at available appointments!</p>
            <a href="booking_page.php" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Upcoming Appointments</h5>
            <p class="card-text">Look at upcoming appointments!</p>
            <a href="upcoming_appointment.php" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Search for Appointment</h5>
            <p class="card-text">Look at upcoming and past appointments, to adjust them, cancel them, or just view them!</p>
            <a href="search_appointment.php" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<footer>
  <p> </p>
</footer>

</html>