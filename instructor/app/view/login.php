<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
?>
<body class="bg-dark">
    <div class="d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
              <?php
				if(isset($_SESSION['error']))
				foreach($_SESSION['error'] as $err){
				echo '<div class="alert alert-danger" role="alert">'. $err . '</div>';}
				unset($_SESSION['error']);
				if (isset($_SESSION['info']))
				foreach($_SESSION['info'] as $info){
					echo '<div class="alert alert-success" role="alert">'. $info . '</div>';}
					unset($_SESSION['info']); ?>
                <div class="login-form">
                    <form action="app/controller/instructor.inc.php?action=login" method="post">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label class="float-right">
                                <a href="?reset">Forgotten Password?</a>
                            </label>
													</div>
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a href="?register" class="btn btn-secondary mt-4">Register</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
define('ContainsBackground', true);
require_once 'footer.php';
?>
