<?php
// Detect the current session
session_start();
// End the current session
session_destroy();
// Redirect to homepage
include('header.php');
header("Location: index.php");
include("footer.php");
exit;
?>