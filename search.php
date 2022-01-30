<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="get" action="">
    <div class="form-group row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="form-group row"> <!-- 2nd row -->
        <label for="keywords" 
               class="col-sm-3 col-form-label">Product Title:</label>
        <div class="col-sm-6">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit">Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>

<?php
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
//if(isset($_GET["keywords"])){
    include_once("mysql_conn.php");
    $SearchText="%".$_GET["keywords"]."%";
    $qry = "SELECT ProductID, ProductTitle FROM product
            WHERE ProductTitle LIKE ? 
            OR ProductDesc LIKE ?
            ORDER BY ProductTitle";
    $stmt=$conn->prepare($qry);
    $stmt->bind_param("ss",$SearchText, $SearchText);
    $stmt->execute();
    $result=$stmt->get_result();
    $stmt->close();
    //$result=$conn->query($qry);
    echo "<div class='row' style='padding:5px'>";
    echo "<div class='col-12'>";
    echo "<p><b>Search result for $_GET[keywords]: </b></p>";
    echo "</div>";
    echo "</div>";
    while ($row=$result->fetch_array()){
	    echo "<div class='col-sm-9' style='padding:5px'>";
        $product="productDetails.php?pid=$row[ProductID]";
        echo "<div class='col-8'>";
        echo "<p><a href=$product>$row[ProductTitle]</a></p>";
        echo "</div>";
        echo "</div>";
    }
    $conn->close();
}

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>