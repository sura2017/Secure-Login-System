<?php
session_start();
session_destroy(); // Delete all session data
header("Location: login.html");
exit();
?>