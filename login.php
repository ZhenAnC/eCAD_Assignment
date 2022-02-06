<?php
// Detect the current Session
session_start();
// Include the Page Layout header
include("header.php");

?>
<head>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>

<script type="text/javascript">
function validateForm()
{
	// Check if passwords match
    if (document.register.password.value != document.register.password2.value){
        alert("Passwords not matched!");
        return false; // cancel submission
    }
    return true;  // No error found
}
</script>

<div class="login_container" id="container">
	<div class="form-container sign-up-container">
		<form name="register" action="addMember.php" method="post"
		onsubmit="return validateForm()">
			<h1>Create Account</h1>
            <br />

			<span>Use your email for registration</span>
			<input class="form-control" name="name" type="text" placeholder="Name" required/>
			<input class="form-control" name="email" type="email" placeholder="Email" required/>
			<input class="form-control"  name="password"type="password" placeholder="Password" required/>
			<input class="form-control"  name="password2" type="password" placeholder="Retype password" required/>
			<button>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="checkLogin.php" method="post">
			<h1>Sign in</h1>
            <br />

			<span>Login with your registered email</span>
			<input type="email" name="email" id="email" placeholder="Email" required />
			<input type="password" name="password" id="password" placeholder="Password" required />
			<a href="forgetPassword.php">Forgot your password?</a>
			<button>Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Log into your member account and start shopping with us for delicious donuts!</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>

<script src="css/login.js"></script>



<?php
//include the pay layout footer
include("footer.php");
?>

