
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../log_in_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Booking Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
          <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Appointments
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="booking_page.php">Book an Appointment</a></li>
            <li><a class="dropdown-item" href="upcoming_appointments.php">Upcoming Appointments</a></li>
            <li><hr class="dropdown-divider" ></hr></li>
            <li><a class="dropdown-item" href="past_appointments.php">Past Appointments</a></li>
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

</body>


</html>