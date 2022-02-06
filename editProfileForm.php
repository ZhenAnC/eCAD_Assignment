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

<form name="update" action="updateProfile.php" method="post"
      onsubmit="return validateForm()">
    <div class="form-group row">
        <div class="col-sm-9">
            <span class="page-title">Update Profile</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="name">Name:</label>
        <div class="col-sm-9">
            <input class="form-control" name="name" 
                   type="text" value=<?php echo $name ?> />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address"
                      cols="25" rows="4"><?php echo $address ?></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country"
                   type="text" value=<?php echo $country ?> />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" 
                   type="text" value=<?php echo $phone ?> />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email"
                   type="email" value=<?php echo $email ?> />
        </div>
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Save</button>
        </div>
    </div>
</form>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>