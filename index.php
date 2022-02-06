<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>

<img src="images/welcome2tastydonuts.png" class="img-fluid" 
     style="display:block; margin:auto;"/>
     
<div style='width:80%; margin:auto;'>
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
$rowIndex = 0;
//Display each product in a row
while ($row=$result->fetch_array()){
     if("$row[Offered]" == "1"){
          //configure values
          $product="productDetails.php?pid=$row[ProductID]";
          $img="./Images/products/$row[ProductImage]";
          $formattedPrice=number_format($row["Price"],2);
          $formattedOfferedPrice = number_format($row["OfferedPrice"],2);

          if ($rowIndex == 0){ //create a new row after 4 products
               echo "<div class='row' style='box-sizing: border-box;'>"; //Start a new row
          }
          echo "<div class='column' style='float:left; width:20%;'>"; //1/4 square in the row
          echo "<a href=$product><img src='$img' class='img-fluid'style='display:block; margin:auto;'/></a>"; //link image with product details page
          echo "<p>$row[ProductTitle]</p>";
          echo "Price: <del><span>S$ $formattedPrice</span></del>"; //delete original price
          echo "<span style='font-weight:bold; font-size:20px; color:red;'> S$ $formattedOfferedPrice !! </span>"; //display offer price
          echo "</div>";
          
          if($rowIndex == 4){ //end the current row if 4 products are shown
               echo "</div>";
               $rowIndex = 0;
          }
          $rowIndex += 1;
     }
}
// To Do:  Ending ....
echo "</div>"; // end of products

$conn->close(); // Close database connnection
echo "</div>"; // End of container
// Include the Page Layout footer
include("footer.php"); 
?>