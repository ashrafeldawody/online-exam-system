<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
?>

<body class="login-body" style="padding:20px">
	<div class="preloader"></div>
	<div class="login-wrap">
<div class="login-html">
		<?php if(isset($_GET['email']) && isset($_GET['token'])){ ?>
			<form class="login-form" id="resetForm" action="app/controller/student.inc.php?action=resetPasswordWithToken" method="post">
				<div class="group">
				<label for="email" class="text-uppercase">Email Address</label>
				<input type="email" name="email" class="input" value="<?php echo $_GET['email'] ?>" readonly>
				</div>
				<div class="group">
				<label for="token" class="text-uppercase">Token</label>
				<input type="text" name="token" class="input" value="<?php echo $_GET['token'] ?>" readonly>
				</div>
				<div class="group">
				<label for="password" class="text-uppercase">New Password</label>
				<input type="password" name="password" class="input" placeholder="Password" minlength="8" required>
				</div>

				<div class="group">
				<button type="submit" class="button">Submit</button>
				</div>

			</form>
		<?php }else{ ?>
			<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Login</label>
			<input id="tab-2" type="radio" name="tab" class="for-pwd"><label for="tab-2" class="tab">Forgot Password</label>
			<div class="login-form">

		<form class="sign-in-htm" id="loginForm" action="app/controller/student.inc.php?action=login" method="post">
			<div class="group">
				<label for="id" class="label">Academic ID</label>
				<input type="text" name="id" class="input input-holder" title="Please Enter Your Real Student ID" placeholder="20*******"  required pattern="\b20\w[0-9]*">
			</div>
			<div class="group">
				<label for="password" class="label">Password</label>
				<input type="password" name="password" class="input input-holder" placeholder="">
			</div>
			<div class="group">
				<input type="submit" class="button" value="Sign In">
			</div>
			<div class="copy-text text-center"><a style="color:white;text-decoration: none;" href="?register">New Student?</a></div>

		</form>
		<form class="for-pwd-htm" id="requestResetForm" action="app/controller/student.inc.php?action=requestReset" method="post">
			<div class="group">
				<label for="email" class="label">Email Address</label>
				<input type="email" name="email" class="input input-holder" placeholder="Email Address" minlength="8" required>
			</div>
			<div class="group">
				<input type="submit" class="button" value="Reset Password">
			</div>
			<div class="hr"></div>

		</form>
		<div class="hr"></div>

	<?php } ?>

	</div>
</div>

</div>

<?php
define('ContainsBackground', true);
require_once 'footer.php';
?>
