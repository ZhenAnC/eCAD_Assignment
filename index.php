<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>

<img src="images/welcome2tastydonuts.png" class="img-fluid" 
     style="display:block; margin:auto;"/>
<div style='width:60%; margin:auto;'>
     <div class="row" style="padding:5px">
	<div class="col-12">
		<br/><span class="page-title" style="color:red;"><?php echo "Products On Offer!"; ?></span>
	</div>
</div>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do:  Starting ....
//Form SQL to retrieve list of products associated to the Category ID
$qry="SELECT * FROM Product ORDER BY ProductTitle ASC";
$stmt=$conn->prepare($qry);
$stmt->execute();
$result=$stmt->get_result();
$stmt->close();

//Display each product in a row
while ($row=$result->fetch_array()){
     if("$row[Offered]" == "1"){
          echo "<div class='row' style='padding:5px'>"; //Start a new row

          //Left column - display a text link showing the product's name,
          //				display the selling price in red in a new paragraph
          $product="productDetails.php?pid=$row[ProductID]";
          echo "<div class='col-8'>"; //67% of row width
          echo "<p><a href=$product>$row[ProductTitle]</a></p>";
          $formattedPrice=number_format($row["Price"],2);
          $formattedOfferedPrice = number_format($row["OfferedPrice"],2);
          echo "Price:<del><span> S$ $formattedPrice</span></del>";
		echo "<span style='font-weight:bold; font-size:20px; color:red;'> S$ $formattedOfferedPrice !! </span>";
     	echo "</div>";
          //Right column - display the product's image
          $img="./Images/products/$row[ProductImage]";
          echo "<div class='col-4'>"; //33% of row width
          echo "<img src='$img'/>";
          echo "</div>";
          echo "</div>";
     }
}
// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
// Include the Page Layout footer
include("footer.php"); 
?>