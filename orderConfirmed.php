<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"])) {	
	include_once("mysql_conn.php");
	echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
	echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
	$qry = "SELECT ShopCartID FROM OrderData WHERE OrderID = ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i",$_SESSION["OrderID"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_array();
	$shopcartID = $row["ShopCartID"];
	$stmt->close();
	
	$qry = "SELECT * FROM shopcartitem WHERE ShopCartID = ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i",$shopcartID);
	$stmt->execute();
	$result=$stmt->get_result();
	$Normal ="";
	$Express ="";
	if ($_SESSION["ShipCharge"] == 2.00)
	{
		$Normal = "Delivery Mode: Normal (Within 1 Day)";
	}
	else
	{
		$Express = "Delivery Mode: Express (Within 2 hours)";
	}
	while ($row=$result->fetch_array())
	{
		echo"</br>";
		echo"Order summary:";
		echo"</br>";
		echo"Product: ", $row['Name'], " x", $row['Quantity'];
		echo"</br>";
		if ($Normal != "")
		{
			echo$Normal;

		}
		else if ($Express != "")
		{
			echo$Express;
		}
		echo"</br>";
		echo"Total Amount: ".number_format($row['Price']*$row['Quantity'],2);
		echo"</br>";
		echo"</br>";
		echo '<a href="index.php">Continue shopping</a></p>';
	}
	$stmt->close();
	$conn->close();
			
} 

include("footer.php"); // Include the Page Layout footer
?>
