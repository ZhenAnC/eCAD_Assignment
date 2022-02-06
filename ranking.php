<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
?>

<?php
include_once("mysql_conn.php"); // Establish database connection handle: $conn
$qry = "SELECT * FROM Product WHERE ProductID=?";
$result = $conn->query($qry);

?>