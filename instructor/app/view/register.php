<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
?>
<body class="bg-dark">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
								<?php
									if(isset($_SESSION['error']))
									foreach($_SESSION['error'] as $err){
									echo '<div class="sufee-alert alert alert-danger alert-dismissible fade show">
									<span class="badge badge-pill badge-danger">Failed</span>'. $err . '</div>';}
									unset($_SESSION['error']);
									if (isset($_SESSION['info']))
									foreach($_SESSION['info'] as $info){
										echo '<div class="sufee-alert alert alert-success alert-dismissible fade show">
										<span class="badge badge-pill badge-success">Success</span>'. $info . '</div>';}
										unset($_SESSION['info']);
										?>
                <div class="login-form">
                    <form action="app/controller/instructor.inc.php?action=register" method="post">
											<div class="form-group">
													<label>Name</label>
													<input type="text" name="name" class="form-control" placeholder="Name" min="5" max="50">
											</div>
											<div class="form-group">
													<label>Invitation Code</label>
													<input type="text" name="invite" class="form-control" <?php echo (isset($_GET['invite'])?('value="' .$_GET['invite'].'" readonly'):'') ?>>
											</div>
												<div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" min=5>
                    		</div>
												<div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" name="phone" class="form-control" placeholder="Phone Number" min=11>
                    		</div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" min=6>
                				</div>
                          <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                          <div class="register-link m-t-15 text-center">
                              <p>Already have account ? <a href="?login"> Sign in</a></p>
                          </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
define('ContainsBackground', true);

require_once 'footer.php';
?>
