<?php
if (!defined('NotDirectAccess')) {
    die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
?>

  <div class="content mt-3">
        <div class="col-md-12">
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
            <div class="card">
              <div class="card-header">
                <strong class="card-title">Courses</strong>
                <button type="button" class="btn btn-outline-success float-right m-1" data-toggle="modal" data-target="#addnewcourse"><i class="fa fa-plus"></i>New Course</button>
                <button type="button" class="btn btn-outline-primary float-right m-1" data-toggle="modal" data-target="#addnewtopic"><i class="fa fa-plus"></i>New Topic</button>
              </div>

              <div class="card-body">
                <table id="CoursesTable" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Tests</th>
                      <th>Questions</th>
                      <th>Topics</th>
                      <th>-</th>
                      <th>-</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $course = new course();
                    $allParents = $course->getAllParents();
                    foreach ($allParents as $parent) { ?>
                      <tr>
                        <td>
                        <h5><?php echo $parent->name ?></h5></td>
                        <td><a href="#" class="badge badge-primary "><?php echo $parent->tests ?></a></td>
                        <td></td>
                        <td><snap class="badge badge-info"><?php echo $parent->childs ?></snap></td>
                        <td>
                          <button type="button" data-toggle="modal" data-target="#editcourse" data-cname="<?php echo $parent->name ?>" data-cid="<?php echo $parent->id ?>" data-cdisabled="true" class="btn btn-outline-info btn-block"><i class="fa fa-magic"></i>Update</button>
                        </td>
                        <?php
                        if ($parent->tests == 0 && $parent->questions == 0 && $parent->childs == 0) {
                            echo '<td><button type="button" class="btn btn-outline-danger btn-block" onclick="javascript: if (confirm(\'Are you sure you want to delete this Course?\')) { window.location.href=\'app/controller/course.inc.php?deleteCourse='. $parent->id .'\' }"><i class="fa fa-trash"></i>Delete</button></td>';
                        } else {
                            echo '<td><button type="button" class="btn btn-outline-danger btn-block" disabled><i class="fa fa-trash"></i>Delete</button></td>';
                        }
                        $allChilds = $course->getAllChilds($parent->id);
                        foreach ($allChilds as $child) { ?>

                          <tr>
                            <td>&nbsp; &nbsp; &nbsp; &nbsp;
                              <?php echo $child->name ?>
                            </td>
                            <td><a href="#" class="badge badge-primary"><?php echo $child->tests ?></a></td>
                            <td><a href="?questions&course=<?php echo $child->id ?>" class="badge badge-warning"><?php echo $child->questions ?></a></td>
                            <td></td>
                            <td>
                              <button type="button" data-toggle="modal" data-cname="<?php echo $child->name ?>" data-cid="<?php echo $child->id ?>" data-pid="<?php echo $child->parent ?>" data-cdisabled="false" data-target="#editcourse" class="btn btn-outline-primary btn-block"><i class="fa fa-magic"></i>Update</button>
                            </td>
                            <?php
                                                  if ($child->tests == 0 and $child->questions == 0) {
                                                      echo '<td><button type="button" class="btn btn-outline-danger btn-block" onclick="javascript: if (confirm(\'Are you sure you want to delete this Course?\')) { window.location.href=\'app/controller/course.inc.php?deleteCourse='. $child->id .'\' }"><i class="fa fa-trash"></i>Delete</button></td>';
                                                  } else {
                                                      echo '<td><button type="button" class="btn btn-outline-danger btn-block" disabled><i class="fa fa-trash"></i>Delete</button></td>';
                                                  }
                                              }
                                          }
                                  ?>
                  </tbody>
                </table>
              </div>
            </div>




        </div>


    <!-- .animated -->

    <div class="modal fade" id="addnewcourse" tabindex="-1" role="dialog" aria-labelledby="addnewcourseLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addnewcourseLabel">Create Course</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <form action="app/controller/course.inc.php?addCourse" id="addcourseForm" method="post">
                  <div class="form-group">
                    <input type="text" name="courseName" placeholder="Enter Name.." class="form-control" required>
                  </div>
                </form>
          </div>
          <div class="modal-footer">
              <button type="submit" form="addcourseForm" class="btn btn-primary btn-block">
                <i class="fa fa-dot-circle-o"></i> Submit
              </button>
          </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="addnewtopic" tabindex="-1" role="dialog" aria-labelledby="addnewtopicLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addnewtopicLabel">Create Topic</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <form action="app/controller/course.inc.php?addCourse" id="addtopicForm" method="post">
                  <div class="form-group">
                    <input type="text" name="courseName" placeholder="Enter Name.." class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="select">Course</label>
                    <select name="course" id="select" class="form-control" required>
                      <?php
                                            $cat = new course;
                                            $parents = $cat->getAllParents();
                                            foreach ($parents as $parent) {
                                                echo '<option value="'. $parent->id .'">'. $parent->name .'</option>';
                                            }
                                            ?>
                    </select>
                  </div>
                </form>
          </div>
          <div class="modal-footer">
              <button type="submit" form="addtopicForm" class="btn btn-primary btn-block">
                <i class="fa fa-dot-circle-o"></i> Submit
              </button>
          </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="editcourse" tabindex="-1" role="dialog" aria-labelledby="editcourseLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editcourseLabel">Edit Course</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <form action="app/controller/course.inc.php?editCourse" id="editcourseForm" method="post">
                  <input type="hidden" name="id">
                  <div class="form-group">
                    <input type="text" name="courseName" placeholder="Enter Course Name.." class="form-control" required>
                  </div>
                  <div class="form-group">
                    <select name="course" class="form-control" required>
                      <option value="0">No Parent</option>
                      <?php
                                            $cat = new course;
                                            $parents = $cat->getAllParents();
                                            foreach ($parents as $parent) {
                                                echo '<option value="'. $parent->id .'">'. $parent->name .'</option>';
                                            }
                                            ?>
                    </select>
                  </div>
                </form>

          </div>
          <div class="modal-footer">
            <button type="submit" form="editcourseForm" class="btn btn-primary btn-sm">
              <i class="fa fa-dot-circle-o"></i> Submit
            </button>
          <div>
        </div>
      </div>
    </div>


  </div>
  <!-- .content -->
  </div>
  <!-- /#right-panel -->
  <?php
		define('ContainsDatatables', true);
        require_once 'footer.php';
        ?>
