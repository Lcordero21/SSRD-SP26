<?php
session_start();
session_destroy();
header("Location:log_in_page.php");
exit();
?>

// this is the logout page, it just destroys the session and goes to login page