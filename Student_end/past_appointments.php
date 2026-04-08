
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: log_in_page.php");
    exit();
}
require_once 'connect_db.php';
$userEmail = $_SESSION['user'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Booking Page</title>
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
          <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Appointments
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="booking_page.php">Book an Appointment</a></li>
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

    <div class="container mt-4">

 <!-- Similar logic to booking page but even simpler, since its just past appts.-->
  <!--I'm not as great with incorporating php within the html lines, so copilot did help a lot with this, I will admit (debugging and reformating)-->
        <h2>Past Appointments</h2>
        <!-- Want to make sure they actually have appts... -->

    <?php
    // this will get the past appts for the user and organize it by most recent first, I also include notes per copilots suggestion
    // format and stuff pulled from ch12 project we did in class and a bit from the booking page for time formatting stuff 
        $sql = "SELECT s.slot_date, s.start_time, s.end_time, u.first, a.description
                FROM appointments a
                JOIN slots s ON a.slot_id = s.id
                JOIN users u ON s.staff_id = u.email
                WHERE a.student_id = ? AND s.slot_date < CURDATE()
                ORDER BY s.slot_date DESC, s.start_time DESC";
        $past = $pdo->prepare($sql);
        $past->execute([$userEmail]);
        $pastAppointments = $past->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class = "container">
    <table class="table">
  <thead class = "table-striped">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Staff</th>
      <th scope="col">Notes</th>
    </tr>
  </thead>
  <tbody class = "table-group-divider">
    <?php
      foreach ($pastAppointments as $row) {
    ?>
    
    <tr>
      <td><?= date('l, F j Y', strtotime($row['slot_date']))?></td>
      <td><?=date('g:ia', strtotime($row['start_time'])) ?> 
            - <?= date('g:ia', strtotime($row['end_time'])) ?></td>
      <td><?=print($row['first']) ?></td> <!--Theres a 1 at the end, maybe html special chars can fix it? idk --> 
      <td><?=print($row['description']) ?></td> <!--Theres also a 1 at the end of this..-->
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>

</div>
</body>


</html>