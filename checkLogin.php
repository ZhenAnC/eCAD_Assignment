<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];


//include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
$qry = "SELECT * FROM shopper WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$loggedIN = FALSE;
while ($row=$result->fetch_array()){
	if (($email == $row["Email"]) && ($pwd==$row["Password"]))
	{
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];

		$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems
			FROM ShopCart sc LEFT JOIN ShopCartItem sci 
			ON sc.ShopCartID=sci.ShopCartID 
			WHERE sc.ShopperID=? AND sc.OrderPlaced=0";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i",$_SESSION["ShopperID"]);
		$stmt->execute();
		$result = $stmt->get_result();
		echo $result->num_rows;

		if ($result->num_rows > 0 ){
			while ($row = $result->fetch_array()){
				$_SESSION["Cart"] = $row["ShopCartID"];
				$_SESSION["NumCartItem"] = $row["NumItems"];

			}
		}
		else{
			$_SESSION["Cart"] = 0;
		}
		// Redirect to home page
		header("Location: index.php");
		$loggedIN = TRUE;
		exit;
	}
	else
	{
		echo "<h3 style='color:red'>Invalid Login Credentials <br /> Please try again</h3>";

	}
}
// $check_qry ="SELECT * FROM `shopper` WHERE Email = '$email' AND Password='$pwd'";

// $result = $conn->query($check_qry);

// if ($result->num_rows > 0)
// {
// 	while ($row = $result->fetch_array()){
		
// 	}
	
// }
// else {
// 	echo "<h3 style='color:red'>Invalid Login Credentials <br /> Please try again</h3>";
// }


// Include the Page Layout footer
include("footer.php");
?>