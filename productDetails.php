<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Display Product information. Starting ....
while ($row=$result->fetch_array()){
    //Display Page Header -
    //Product's name is read from the "ProductTitle" column of "product" table
    echo "<div class='row'>";
    echo "<div class='col-sm-12' style='padding:5px'>";
    echo "<span class='page-title'>$row[ProductTitle]</span>"; 
    echo "</div>";
    echo "</div>";

    //left column - display the product's image
    echo "<div class='row'>";
    $img="./Images/products/$row[ProductImage]";
    echo "<div class='col-sm-3' style='padding:5px; display: block;'>";
    echo "<span><img src=$img / class='img-fluid'></span>";
    echo "</div>";

    //right column - display the product's description
    echo "<div class='col-sm-9' style='padding:5px; display:block;'>";
    echo "<p>$row[ProductDesc]</p>";

    //right column - display the product's specification
    $qry="SELECT s.SpecName, ps.SpecVal from productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=?
            ORDER BY ps.priority";
    $stmt=$conn->prepare($qry);
    $stmt->bind_param("i",$pid); //"i" - integer
    $stmt->execute();
    $result2=$stmt->get_result();
    $stmt->close();
    while ($row2 = $result2->fetch_array()){
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br />";
    }
    echo "<br/><br/><br/>";
    //Right column - display the product's price
    $formattedPrice = number_format($row["Price"],2);
    
    if("$row[OfferedPrice]" == NULL){ //if product has no offered price (it is not on offer)
        echo "Price:<span style='font-weight:bold; color:red;'>
            S$ $formattedPrice</span>";
        $_SESSION["offerPrice"] = NULL;
    }
    else{
        $formattedOfferedPrice = number_format($row["OfferedPrice"],2);
        echo "Price: <del><span>S$ $formattedPrice</del></span>
        <span style='font-weight:bold; font-size:20px; color:red;'>S$ $formattedOfferedPrice </span>";
        $_SESSION["offerPrice"] = $row["OfferedPrice"];
    }
    
}

$qry = "SELECT Quantity from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result3 = $stmt->get_result();
$stmt->close();
$row = $result3->fetch_row();
if($row[0] > 0){ //if product is in stock, show 'Add to Cart' button
        // Create a Form for adding the product to shopping cart. Starting ....
        echo "<form action='cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";
        echo "Quantity: <input type='number' name='quantity' value='1'
                min='1' max='10' style='width:40px' required />";
        echo "<button type='submit' style='background-color: #E0C5C5; color: #2F1D1D;'>Add to Cart</button>";
        echo "</form>";
}
else{
        echo "<br/><span style='font-weight:bold; color:red; font-size:20px;'>Out of Stock";
}
echo "</div>"; //End of right column
echo "</div>"; //End of row

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
