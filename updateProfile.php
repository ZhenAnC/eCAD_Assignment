<?php
session_start(); // Detect current session

// Read data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];

// Include PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Define UPDATE SQL statement
$qry = "UPDATE shopper
        SET Name='$name', Address='$address', Country='$country',
        Phone='$phone', Email='$email', Password='$password'
        WHERE ShopperID=$_SESSION["ShopperID"]";
$stmt = $conn->prepare($qry);

if ($stmt->execute()) { // SQL statement executed successfully
    // Successful message
    $message = "Update successful!";
    header("Location: profile.php");
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