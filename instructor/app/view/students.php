<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
$_student = new student;
?>

<div class="card">
  <div class="card-header text-center">
    <strong class="card-title">My Students</strong>
  </div>

  <div class="card-body">
    <table id="allStudents" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>Status</th>
          <th>-</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $students = $_student->getMyStudents();
        foreach ($students as $student) { ?>
          <tr>
            <td><strong><?php echo $student->id ?></strong></td>
            <td><?php echo $student->name ?></td>
            <td><?php echo $student->email ?></td>
            <td><?php echo $student->phone ?></td>
            <td><?php echo (($student->suspended)?'<span class="badge badge-danger">Suspended</span>':'<span class="badge badge-success">Active</span>') ?></td>
          	<td>
            <a type="button" href="?results&studentID=<?php echo $student->id ?>" class="btn btn-outline-success btn-block">
              <i class="fa fa-trash"></i> View All Results</a>
            </td>

          </tr>
          <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php
  define('ContainsDatatables', true);
  require_once 'footer.php';
  ?>
