

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
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry="SELECT *, (Price*Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID=?";
	$stmt=$conn->prepare($qry);
	$stmt->bind_param("i",$_SESSION["Cart"]);
	$stmt->execute();
	$result=$stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>";
		echo "<tr>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<td width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; //end of header row
		echo "</thead>"; //end of table's header section
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();		
		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br/>";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice=number_format($row["Price"],2);
			echo "<td>$formattedPrice</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for($i =1; $i <= 10; $i++){
				if($i==$row["Quantity"])
					$selected="selected";
				else
					$selected="";
				echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			echo "</form>";
			echo "</td>";
			$formattedTotal=number_format($row["Total"],2);
			echo "<td>$formattedTotal</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='$row[ProductID]'/>";
			echo "<input type='image' src='images/trash-can.png' title='Remove Item'/>";
			echo "</form>";
			echo "</td>";
			echo "</tr>";
		
			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][]=array("productId"=>$row["ProductID"],
										"name"=>$row["Name"],
										"price"=>$row["Price"],
										"quantity"=>$row["Quantity"]);
			// Accumulate the running sub-total
			$subTotal += $row["Total"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
				
		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart
	
		
		

		$_SESSION["SubTotal"]=round($subTotal,2);	
		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		echo "<form method='post' action='checkoutProcess.php'>";
		
		echo"<container style='text-align:right; align-content:flex-start; padding-right:50px'>";
		echo"<text style='font-size:30px;'>Choose delivery type<text>";
		echo"</br>";
		echo"<input type='radio' name='Delivery' id='delivery_normal' value='2' checked /> ";
		echo"<label for='delivery_normal' style='text-align:left; font-size:15px; margin-left: 5px; margin-bottom: -200px;'><strong>$2</strong> (Normal delivery within 1 day) </label> ";
		echo"</br>";
		echo"<input type='radio' name='Delivery' id='delivery_express' value='5' />";
		echo"<label for='delivery_express' style='font-size:15px; margin-left: 5px; '> <strong>$5</strong> (Express delivery within 2 hours) </label>";
		if (!empty($_GET['Delivery'])){ 
			$shipping = $_GET['Delivery'];
		}
		else{ $shipping = '2';}
		echo"</br>";
		echo"Shipping Cost = ";
		echo"<span class='r-text'> $shipping</span>";
		// if ($shipping == 2)
		// {
		// 	$subTotal+=2;
		// }
		// else
		// {
		// 	$subTotal+=5;
		// }
		#echo"$subTotal";
		echo "<p style='text-align:right;padding-right:50px; font-size:20px'>
		Subtotal=S$ ". number_format($subTotal,2); echo "</p>";
		echo "<input type='image' style='float:right; padding-right:50px; '
		src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		echo"</container>";	
		
		
		#echo"<span class='r-text'>Total Price=S$ ". number_format($subTotal,2) ," +S$$shipping(shipping) = S$ ".number_format($subTotal,2) + $shipping , "</span>";
		#echo "<p style='text-align:right; padding-right:50px; padding-top: -150px; font-size:20px'>;
		echo"</br>";
		echo"</br>";
		
		echo"<script>
			$('input[type=radio]').click(function(e) {//jQuery works on clicking radio box
				
				var value = $(this).val(); //Get the clicked checkbox value
				
				var name= '<?php echo $_SESSION[SubTotal];?>'
				value = parseFloat(value)+name
				$('.r-text').html(value);
			});
		</script>
		";
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
