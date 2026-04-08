<?php
session_start();
session_destroy();
header("Location:http://localhost/SSRD%20SP26/log_in_page.php");
exit();
?>