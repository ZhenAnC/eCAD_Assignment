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
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$sid = $_GET["sid"] // Read ShopperID from query string
$qry = "SELECT * FROM shopper WHERE ShopperID = '$_SESSION["ShopperID"]'";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $sid); // "i" - integer
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while ($row = $result->fetch_array()) {
    $name = $result["Name"];
    $address = $result["Address"];
    $country = $result["Country"];
    $phone = $result["Phone"];
    $email = $result["Email"];
}
?>

<div style="width:80%; margin:auto;">
    <h1>Profile</h1>
    <p>Name: <?php echo $name; ?></p>;
    <p>Address: <?php echo $address; ?></p>;
    <p>Name: <?php echo $country; ?></p>;
    <p>Name: <?php echo $phone; ?></p>;
    <p>Name: <?php echo $email; ?></p>;
    <form name="edit" action="editProfileForm.php" method="post">
        <button>Edit profile</button>
    </form>
</div>

<?php
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