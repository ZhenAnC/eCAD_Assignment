<?php
session_start(); // Detect current session

// Read data input from previous pages
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$security = $_POST["security"];
$answer = $_POST["answer"];
// Create a password hash using the default bcrypt algorithm
//$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Define INSERT SQL statement
$qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($qry);
// "sss" - 3 string parameters
$stmt->bind_param("ssssssss", $name, $address, $country, $phone, $email, $password, $security, $answer);

if ($stmt->execute()) {
    // Retrieve Shopper ID assigned to new shopper
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry); // execute SQL and get returned result
    while ($row = $result->fetch_array()) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }

    // Success message & shopper ID
    $message = "Registration successful!<br />
                Your ShopperID is $_SESSION[ShopperID]<br />";
    // Save shopper name in session var
    $_SESSION["ShopperName"] = $name;
}
else { // Error message
    $message = "<h3 style='color:red'>An error has occurred. Registration failed.</h3>";
}

// Release resource allocated for prepared statement
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