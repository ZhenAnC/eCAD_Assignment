

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price*Quantity) AS Total
			FROM shopcartitem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);  // "i' - integer
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// the page header and header row of shopping cart page
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>"; // Start of table's header section
		echo "<tr>"; // Start of header row
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; // End of header row
		echo "</thead>"; // End of table's header section
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		$items = 0;
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br />";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td>"; // Column for update quantity of purchase
			echo "<form action='cartFunctions.php' method='post'>";
			$i = $row["Quantity"];
			echo "<input type='number' name='quantity' onChange='this.form.submit()' value='$i' />";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"],2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // Column for removing item from shopping cart
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='images/trashcan.png' title='Remove Item' />";
			// trashcan url: https://www.pinclipart.com/pindetail/iboJRTJ_trash-can-cartoon-png-clipart/
			echo "</form>";
			echo "</td>";
			echo "</tr>";
		    // Store the shopping cart items in session variable as an associate array
				$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
											 "name"=>$row["Name"],
											 "price"=>$row["Price"],
											 "quantity"=>$row["Quantity"]);
			// Accumulate the running sub-total
			$subTotal += $row["Total"];
			// Accumulate the total number of items
			$items += $row["Quantity"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
		
		// Display the subtotal at the end of the shopping cart
		echo "<p style='text-align:right; font-size:20px'>
				Number of Items in Cart = ".$items;
		
		// Display the subtotal at the end of the shopping cart
		echo "<p style='text-align:right; font-size:20px'>
				Subtotal = S$". number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);

		
		if ($_SESSION["SubTotal"] > 50.00) {
			echo"</br>";
			echo"<text style='font-size:17px;'>Your delivery is free if you choose normal delivery as you have spent more than $50.<text>";
		}
		else {
			echo"</br>";
			echo"<text style='font-size:17px;'>Spend more than S$50 for free normal delivery. You are not eligible yet.<text>";
		}

		// Add PayPal Checkout button on the shopping cart page
		echo "<form style='text-align:right' method='post' action='checkoutProcess.php'>";
		echo"<text style='font-size:20px;'>Choose delivery type:<text>";
		echo"</br>";
		echo"<input type='radio' name='Delivery' id='delivery_normal' onClick='handleClick(this);' value='2' checked /> ";
		echo"<label for='delivery_normal' style='text-align:left; font-size:15px; margin-left: 5px; margin-bottom: -200px;'><strong>$2</strong> (Normal delivery within 1 day) </label> ";
		echo"</br>";
		echo"<input type='radio' name='Delivery' id='delivery_express' onClick='handleClick(this);' value='5' />";
		echo"<label for='delivery_express' style='font-size:15px; margin-left: 5px; '> <strong>$5</strong> (Express delivery within 2 hours) </label>";
		echo"</br>";
		echo "<input type='image' style='float:right;'
		src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		echo"</br>";
		echo"</br>";
		echo"<strong>*Total price will be updated in checkout</strong>";
		echo"</container>";	
		if (!empty($_GET['Delivery'])){ 
			$shipping = $_GET['Delivery'];
		}
		else{ $shipping = '2';}
		echo"</br>";

		
		#echo"<span class='r-text'>Total Price=S$ ". number_format($subTotal,2) ," +S$$shipping(shipping) = S$ ".number_format($subTotal,2) + $shipping , "</span>";
		#echo "<p style='text-align:right; padding-right:50px; padding-top: -150px; font-size:20px'>;
		echo"</br>";
		echo"</br>";
		echo"<script>
				var currentValue = <?php echo json_encode($_SESSION[SubTotal]) ?>;

				function handleClick(myRadio) {
				alert('Old value: ' + currentValue);
				alert('New value: ' + myRadio.value);
				currentValue = myRadio.value;
			}
		</script>";
		

		// 	$('input[type=radio]').click(function(e) {//jQuery works on clicking radio box
				
		// 		var value = $(this).val(); //Get the clicked checkbox value
				
		// 		var name= '<?php echo $_SESSION[SubTotal];
		// 		value = parseFloat(value)+name
		// 		$('.r-text').html(value);
		// 	
	

		echo"</br>";
		echo"</br>";
		echo "</form>";	

		
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
