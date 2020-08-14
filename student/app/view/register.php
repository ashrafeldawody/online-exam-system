<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
 ?>
<body class="login-body">
	<div class="preloader"></div>
	<div class="login-wrap" style="min-height: 750px;">
<div class="login-html">
	<label for="tab-1" class="tab text-center">Register</label>
	<div class="login-form">
		<?php if(isset($_GET['id'])){
			$_student = new student;
			$student = $_student->getByID($_GET['id']);
			?>
		<form class="siggn-in-htm" id="registerForm" action="app/controller/student.inc.php?action=register" method="post">
			<div class="group">
			<label for="id" class="label">Student ID</label>
			<input type="text" id="id" name="id" class="input input-holder" value="<?php echo $student->id ?>" readonly>
			</div>
			<div class="group">
			<label for="name" class="label">Full Name in Arabic</label>
			<input type="text" id="name" name="name" class="input input-holder" value="<?php echo $student->name ?>" disabled>
			</div>
			<div class="group">
			<label for="email" class="label">Email</label>
			<input type="email" id="email" name="email" class="input input-holder" minlength="12" required>
			</div>
			<div class="group">
			<label for="phone" class="label">Phone Number</label>
			<input type="tel" id="phone" name="phone" class="input input-holder" minlength="11" required>
			</div>
			<div class="group">
			<label for="password" class="label">Password</label>
			<input type="password" id="password" name="password" class="input input-holder" minlength="8" required>
			</div>
			<div class="group">
					<input type="submit" class="button" value="Register">
			</div>
			<div class="copy-text text-center"><a style="color:white;text-decoration: none;" href="?login">Already Registered?</a></div>
		</form>
	<?php }else{ ?>
		<form class="siggn-in-htm" id="checkIDForm" action="app/controller/student.inc.php?action=checkID" method="post">
			<div class="group">
			<label for="id" class="label">Student ID</label>
			<input type="text" name="id" class="input input-holder" minlength="9" title="Please Enter Your Real Student ID" placeholder="20*******"  required pattern="\b20\w[0-9]*">
			</div>
			<div class="group">
					<input type="submit" class="button" value="Next Step">
			</div>
			<div class="copy-text text-center"><a style="color:white;text-decoration: none;" href="?login">Already Registered?</a></div>
		</form>
	<?php }?>
	</div>
</div>
</div>
<?php
define('ContainsBackground', true);
require_once 'footer.php';
?>
