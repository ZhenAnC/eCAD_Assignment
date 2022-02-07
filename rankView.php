<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

// Read data input from previous page
$productID = $_POST["product"];
$rank = $_POST["rank"];
$comment = $_POST["comment"];

// Include PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Define UPDATE SQL statement
$qry = "UPDATE ranking
        SET ProductID='$productID', Rank='$rank', Comment='$comment'";
$stmt = $conn->prepare($qry);

if ($stmt->execute()) { // SQL statement executed successfully
    // Successful message
    $message = "Update successful!";
}
else {
    $message = "<h3 style='color:red'>Error in updating profile.</h3>";
}

// Release the resource allocated for prepared statement
$stmt->close();
// Close database connection
$conn->close();

// Display Page Layout header with updated session state and links
include("header.php");
// Display message
echo $message;
// Display Page Layout footer
include("footer.php");
?>