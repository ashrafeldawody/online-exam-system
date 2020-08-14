<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
?>
<body class="bg-dark">
<?php if (isset($_GET['token'])){
	?>
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
                    <form action="app/controller/instructor.inc.php?action=resetPassword" method="post">
											<div class="form-group">
													<label>Token</label>
													<input type="text" name="token" value= "<?php echo $_GET['token'];?>"class="form-control" readonly>
											</div>
											<div class="form-group">
													<label>Email address</label>
													<input type="email" name="email" class="form-control" placeholder="Email">
											</div>

											<div class="form-group">
													<label>New Password</label>
													<input type="password" name="password" class="form-control" placeholder="New Password">
											</div>
											<div class="form-group">
													<label>Retype Password</label>
													<input type="password" name="repassword" class="form-control" placeholder="Retype Password">
											</div>
                            <button type="submit" class="btn btn-primary btn-flat m-b-15">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php } else{ ?>
		<div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                        <img class="align-content" src="view/images/logo.png" alt="">
                </div>
                <div class="login-form">
                    <form action="app/controller/instructor.inc.php?action=requestReset" method="post">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                            <button type="submit" class="btn btn-primary btn-flat m-b-15">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
<?php 	}
define('ContainsBackground', true);

require_once 'footer.php';
?>
