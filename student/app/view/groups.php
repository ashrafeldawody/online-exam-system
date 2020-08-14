<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';

		$grp = new group();
		$myGroups = $grp->getMyGroups();

		?>
		<body class="bg-light" style="min-width:650px">

    <div class="container mt-3">
      <div class="card">
        <div class="card-header">
          <strong class="card-title">Groups
          </strong>
          <button type="button" class="btn btn-outline-primary float-right mb-1" data-toggle="modal" data-target="#joinGroup"><i class="fa fa-plus"></i>Join Group</button>

        </div>
        <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Test</th>
                  <th scope="col">Instructor</th>
                  <th scope="col">End date</th>
                  <th scope="col">-</th>
                </tr>
              </thead>
              <tbody>

								<?php foreach($myGroups as $group){ ?>
									<tr>
										<th><?php echo $group->name ?></th>
										<td><?php echo $group->instructor ?></td>
										<td><?php echo date('m-d-Y h:i:s A', strtotime($group->joinDate));?></td>
										<td>
											<button type="button" data-id="<?php echo $group->id ?>" class="btn btn-outline-danger mb-1 leaveGroupbtn"<i class="fa fa-plus"></i>Leave Group</button>
									</td>
									</tr>
								<?php } ?>

              </tbody>
            </table>
</div>
</div>
</div>

<div class="modal fade" id="joinGroup" tabindex="-1" role="dialog" aria-labelledby="joinGroupTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Join Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="joinGroupModal" action="app/controller/group.inc.php?action=joinGroup" method="post">
            <div class="form-group">
              <label for="exampleInputEmail1">Code</label>
              <input type="text" class="form-control"placeholder="Enter Group Code">
              <small class="form-text text-muted">Don't Join Groups you are not allowed to.</small>
            </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="joinGroupModal" class="btn btn-primary">Join</button>
      </div>
    </div>
  </div>
</div>
<?php
require_once 'footer.php';
?>
