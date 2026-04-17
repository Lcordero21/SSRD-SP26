<?php
// This is what makes all the empty slots for the booking pages, has to be run once before running the website...
// definitely adding to future work stuff in final presentation  

require_once 'connect_db.php';
// Bishop Hours are from 8 to 5 Monday to Friday. With a 1 hour lunch break from 12 to 1. 
$startHour = 8; // 8am
$endHour = 17; // 5pm
$daysAhead = 30;
$staffEmail = 'admin@willamette.edu'; //This is my admin email in this test website, future work though: will make it more secure 



for ($i = 1; $i < $daysAhead; $i++) {
    $date = date('Y-m-d', strtotime("+$i days"));
    $dayOfWeek = date('N', strtotime($date)); // 1=Mon, 7=Sun

    if ($dayOfWeek >= 6) continue; //skips weekends

    //creates a slot for each hour, excluding the lunch break (at 12pm)
    for ($hour = $startHour; $hour < $endHour; $hour++) {
        if ($hour == 12) continue; //skips lunch break
        $startTime = sprintf('%02d:00:00', $hour);
        $endTime   = sprintf('%02d:00:00', $hour + 1);

        // checks to make sure it inst duplicatting existing slots
        $checkDuplicate = $pdo->prepare("SELECT id FROM slots WHERE slot_date = ? AND start_time = ? AND start_time != '12:00:00'");
        $checkDuplicate->execute([$date, $startTime]);

        if (!$checkDuplicate->fetch()) {
            $new = $pdo->prepare("INSERT INTO slots (staff_id, slot_date, start_time, end_time) VALUES (?, ?, ?, ?)");
            $new->execute([$staffEmail, $date, $startTime, $endTime]);
            echo "Created slot for $date from $startTime to $endTime\n";
            //echo is for debugging stuff, I'm not going to comment it out in the final version for admin stuff sake
        }
    }
}
?>
