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

$shopperID = $_SESSION["ShopperID"];
$qry = "SELECT * FROM shopper WHERE ShopperID=$shopperID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_array()) {
    $name = $row["Name"];
    $address = $row["Address"];
    $country = $row["Country"];
    $phone = $row["Phone"];
    $email = $row["Email"];
}
?>

<div style="width:80%; margin:auto;">
<form name="editProfile" action="editProfileForm.php" method="post">
    <div class="form-group row offset-sm-3">
        <div class="col-sm-9">
            <span class="page-title">Profile</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3" for="name">Name:</label>
        <div class="col-sm-9">
            <span><?php echo $name; ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3" for="address">Address:</label>
        <div class="col-sm-9">
            <span><?php echo $address; ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3" for="country">Country:</label>
        <div class="col-sm-9">
            <span><?php echo $country; ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3" for="phone">Phone:</label>
        <div class="col-sm-9">
            <span><?php echo $phone; ?></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <span><?php echo $email; ?></span>
        </div>
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Edit</button>
        </div>
    </div>
</form>
</div>

<?php
// Release the resource allocated for prepared statement
$stmt->close();
// Close database connection
$conn->close();

// Display Page Layout footer
include("footer.php");
?>