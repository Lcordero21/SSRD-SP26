<?php
session_start();
session_destroy();
header("Location:http://localhost/SSRD%20SP26/Student_end/homepage.php");
exit();
?>