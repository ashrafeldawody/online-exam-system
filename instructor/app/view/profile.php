<?php
   if (!defined('NotDirectAccess')){
   	die('Direct Access is not allowed to this page');
   }
   require_once 'header.php';
   require_once 'navbar.php';
  $inst = new instructor();
  $instructor = $inst->getByEmail($_SESSION['mydata']->email);

   ?>

<div class="content" style="margin-top:50px">
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
   <div class="row">


      <div class="col-lg-6">
      <div class="card">
         <div class="card-header">
            <strong>Update</strong> Information
         </div>
         <div class="card-body card-block">
      <form action="app/controller/instructor.inc.php?action=updateInfo" method="post" id="updateInfo" class="form-horizontal">
				<input type="hidden" name="id" value="<?php echo $instructor->id;?>" >
				<div class="row form-group">
					<div class="col col-md-3"><label for="profname" class=" form-control-label">Name</label></div>
					<div class="col-12 col-md-9">
					<input type="text" name="profname" value="<?php echo $instructor->name;?>" required="" class="form-control">
					</div>
				</div>
				<div class="row form-group">
					<div class="col col-md-3"><label for="email" class="form-control-label">Email Address</label></div>
					<div class="col-12 col-md-9"><input type="email" name="email" value="<?php echo $instructor->email;?>" placeholder="Enter Email" class="form-control"></div>
				</div>
				<div class="row form-group">
					<div class="col col-md-3"><label for="phonenum" class=" form-control-label">Phone Number</label></div>
					<div class="col-12 col-md-9">
					<input type="text" name="phonenum" value="<?php echo $instructor->phone;?>" required="" class="form-control">
					</div>
				</div>


            </form>
         </div>

       <div class="card-footer text-center">
          <button type="submit" form="updateInfo" class="btn btn-primary btn-sm">
          <i class="fa fa-dot-circle-o"></i> Update
          </button>
       </div>
      </div>

   </div>
   <div class="col-lg-6">
         <div class="card">
         <div class="card-header">
            <strong>Update</strong> Password
         </div>
         <div class="card-body card-block" >
            <form action="app/controller/instructor.inc.php?action=updatePassword" id="updatePassword" method="post" class="form-horizontal">
              <input type="hidden" name="email" value="<?php echo $instructor->email;?>">
               <div class="row form-group">
                  <div class="col col-md-3"><label for="new-password" class=" form-control-label">New Password</label></div>
                  <div class="col-12 col-md-9"><input type="password" name="password"  placeholder="Enter New Password..." class="form-control"></div>
               </div>
               <div class="row form-group">
                  <div class="col col-md-3"><label for="hf-password" class=" form-control-label">Retype New Password</label></div>
                  <div class="col-12 col-md-9"><input type="password" name="repassword" placeholder="Retype New Password..." class="form-control"></div>
               </div>
            </form>
         </div>
         <div class="card-footer text-center">
            <button type="submit" form="updatePassword" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> Update
            </button>
            </div>
      </div>

   </div>
   </div>
</div>
<!-- .animated -->
</div> <!-- .content -->
</div><!-- /#right-panel -->
<?php
require_once 'footer.php';
?>
