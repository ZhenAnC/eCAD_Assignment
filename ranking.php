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
include_once("mysql_conn.php"); // Establish database connection handle: $conn
?>

<!-- Create a centrally located container -->
<div style="width:80%; margin:auto;">
	<form name='rank' action='rankView.php' method='post'>
		<div class='form-group row offset-sm-3'>
			<div class='col-sm-9'>
				<span class='page-title'>Rank your favourite product!</span>
			</div>
		</div>
		<div class='from-group-row'>
			<div class='col-sm-9'>
				<label class='col-form-label' for='product'>Select a product:</label>
			</div>
		</div>
		<div class='form-group-row'>
			<select name='product' class='form-control'>
				<option disabled selected hidden>Choose a product</option>
				<?php
				$qry = "SELECT * FROM Product";
				$result = $conn->query($qry);
				while ($row = $result->fetch_array()) {
					echo '<option>'.$row['ProductTitle'].'</option>';
				}
				?>
			</select>
		</div>
		<div class='col-sm-9'>
			<label class='col-form-label' for='rank'>Give it a rank:</label>
		</div>
		<div class='form-group-row'>
			<select name='rank' class='form-control'>
				<option disabled selected hidden>Choose a rank</option>
				<option>1 (Worst)</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5 (Best)</option>
			</select>
		</div>
		<div class='col-sm-9'>
			<label class='col-form-label' for='comment'>Comments:</label>
		</div>
		<div>
			<textarea class="form-control" name="comment"
					cols="25" rows="4"></textarea>
		</div>
		<div class="form-group row">       
			<div class="col-sm-9 offset-sm-3">
				<button type="submit">Submit</button>
			</div>
		</div>
	</form>
</div>

<!-- Closing container -->
<?php 
include("footer.php"); // Include the Page Layout footer
?>