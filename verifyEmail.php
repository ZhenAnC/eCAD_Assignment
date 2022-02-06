<?php 
include("header.php"); 
session_start();// Include the Page Layout header
// if (! isset($_SESSION["ForgetPassword"])) { // Check if user logged in 
// 	// redirect to login page if the session variable shopperid is not set
// 	header ("Location: forgetPassword.php");

// 	exit;
// }

include_once("mysql_conn.php");
?>


<!-- Create a centrally located container -->
<div style="width:80%; margin:auto;">
<form method="post">
	<div class="form-group row">
		<div class="col-sm-9 offset-sm-3">
			<span class="page-title">Forget Password</span>
			</br>
			<span>This is the 2nd step of resetting your password</span>
</br>
			<span>Please answer your security question in order to reset your password</span>
		</div>
	</div>
	<div class="form-group row">
        <label class="col-sm-3 col-form-label">
            Question:</label>
    
        <?php
        // Read email address entered by user

        // Retrieve shopper record based on e-mail address
        include_once("mysql_conn.php");
        $qry = "SELECT * FROM Shopper WHERE Email=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("s", $_SESSION["Email"]); 	// "s" - string 
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0)
        {
            $row = $result-> fetch_array();
            echo"<div class='col-sm-9'>";
            echo"<label class='col-sm-3 col-form-label' for='eMail'>$row[PwdQuestion]</label>";
            echo"</div>";

        }
        ?>
	</div>
    <div class="form-group row">
    <label class="col-sm-3 col-form-label">
            Answer:</label>
            <div class="col-sm-9">
			<input class="form-control" name="pwdQns" id="pwdQns"
                   type="pwdQns" required />
		</div>
    </div>
    
	<div class="form-group row">      
		<div class="col-sm-9 offset-sm-3">
			<button type="submit">Submit</button>
		</div>
	</div>
</form>
<?php 
if (isset($_POST["pwdQns"])) {
	// Read email address entered by user
	$pwdQns = $_POST["pwdQns"];
	#$_SESSION["Email"] = $eMail;
	
	// Retrieve shopper record based on e-mail address
	include_once("mysql_conn.php");
	$qry = "SELECT * FROM shopper WHERE PwdAnswer=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $pwdQns); 	// "s" - string 
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	if ($result->num_rows > 0) {
        $row = $result-> fetch_array();
		$shopperId = $row["ShopperID"];
		$new_pwd = "ecader";
        //hash default password
		$qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("si",$new_pwd,$shopperId);
		$stmt->execute();
		$stmt->close();
        echo"Your new password is now ", "<strong>",$new_pwd,"</strong>";
        echo"</br>";
        echo"Please login and reset your password!";
		
	}
	else {
		echo "<p><span style='color:red;'>
		      Wrong Answer!</span></p>";
	}
	$conn->close();
}
?>