<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
$group = new group();
$allGroups = $group->getAll($_SESSION['mydata']->id);
?>
  <div class="content mt-3">
    <div class="animated fadeIn">
        <div class="col-md-12">
          <?php
					if(isset($_SESSION['error']))
					foreach($_SESSION['error'] as $err){
					echo '<div class="alert alert-danger alert-dismissible fade show">
					<span class="badge badge-pill badge-danger">Failed</span>'. $err . '</div>';}
					unset($_SESSION['error']);
					if (isset($_SESSION['info']))
					foreach($_SESSION['info'] as $info){
						echo '<div class="alert alert-success">
						<span class="badge badge-success">Success</span>'. $info .'</div>';}
						unset($_SESSION['info']);
						if (isset($_GET['invitations']) && isset($_GET['id'])) { ?>
								<button type="button" class="btn btn-danger float-right" onclick="javascript: if (confirm('Are you sure you want to delete all the invitation Codes?'))
									{ window.location.href='app/controller/group.inc.php?clearInvites=<?php echo $_GET['id'] ?>';}">
									<i class="fa fa-envelope"></i> Clear Invitations
								</button>
								<button type="button" class="btn btn-primary float-right" style="margin-right:20px" data-toggle="modal" data-target="#createGroupInvites">
									<i class="fa fa-plus"></i> Generate New Codes
								</button>
								<button type="button" class="btn btn-warning float-right" style="margin-right:20px" data-toggle="modal" data-target="#addstudent">
									<i class="fa fa-plus"></i> Add Students
								</button>

							<table id="invitationsTable" class="table table-striped">
				        <thead>
				            <tr>
				                <th>#</th>
				                <th>Group</th>
				                <th>Invite Code</th>
				                <th>-</th>
				            </tr>
				        </thead>
				        <tbody>
											<?php
												$codes = $group->getInvitations($_GET['id']);
												$counter = 1;
												foreach ($codes as $code){
													echo '<tr><td>'. $counter++ .'</td>';
													echo '<td>'. $code->name .'</td>';
													echo '<td>'. $code->code .'</td>';
													echo '<td><a class="btn btn-outline-danger" href="app/controller/group.inc.php?deleteInvite='. $code->code .'"><i class="fa fa-trash"></i>Delete This Code</a></td></tr>';
												 } ?>
								</tbody>
							</table>
							<div class="modal fade" id="createGroupInvites" tabindex="-1" role="dialog">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title">Generate Group Invitations</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <div class="modal-body">
											<form action="app/controller/group.inc.php?createInvites" id="createInvitesForm" method="post">
												<div class="form-group">
													<input type="hidden" name="groupID" value="<?php echo $_GET['id']; ?>">
													<input type="text" name="prefix" placeholder="Prefix (optional).." class="form-control">
												</div>
												<div class="form-group">
													<input type="number" name="count" placeholder="Number Of Codes To Generate.." min="1" max="1000" class="form-control" required>
												</div>
											</form>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" form="createInvitesForm" class="btn btn-primary">Generate</button>
							      </div>
							    </div>
							  </div>
							</div>
							<div class="modal fade" id="addstudent" tabindex="-1" role="dialog">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title">Add Students to Group</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <div class="modal-body">
											<form action="app/controller/group.inc.php?addStudents" id="addstudentForm" method="post">
												<input type="hidden" name="groupID" value="<?php echo $_GET['id']; ?>">
												<div class="form-group">
											    <label for="studentIDs">Stuudent IDs</label>
											    <textarea class="form-control" id="studentIDs" name="studentIDs" rows="10" placeholder="Write One ID per line."></textarea>
											  </div>
											</form>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" form="addstudentForm" class="btn btn-primary">Add</button>
							      </div>
							    </div>
							  </div>
							</div>

						<?php
						}elseif (isset($_GET['viewMembers']) && isset($_GET['id'])) {
						if(isset($_SESSION['valid']) || isset($_SESSION['nonValid'])){?>
						<script>
						$(window).on('load',function(){
						    var delayMs = 200; // delay in milliseconds
						    setTimeout(function(){
						        $('#addReport').modal('show');
						    }, delayMs);
						});
						</script>

						<div class="modal fade" id="addReport">
						      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
						            <div class="modal-content">
						                <div class="modal-header">
						                    <h4 class="modal-title">Adding Students to Group</h4>
						                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                        <span aria-hidden="true">×</span>
						                    </button>
						                </div>
						                <div class="modal-body">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th>ID</th>
																		<th>Status</th>
																	</tr>
																</thead>
																<tbody>
																	<?php if(!empty($_SESSION['nonValid'])){
																		foreach($_SESSION['nonValid'] as $id){ ?>
																			<tr>
																				<th><?php echo $id ?></th>
																				<td><span class="badge badge-danger">Doesn't Exist in system</span></td>
																			</tr>
																		<?php } }
																		if(!empty($_SESSION['valid'])){
																		foreach($_SESSION['valid'] as $id){ ?>
																		<tr>
																			<th><?php echo $id ?></th>
																			<td><span class="badge badge-success">Added</span></td>
																		</tr>
																	<?php } }?>
																</tbody>
																</table>

						                </div>
						            </div>
						      </div>
						</div>
					<?php
					unset($_SESSION['valid']);
					unset($_SESSION['nonValid']);
					} ?>
						<div class="card">
							<div class="card-header">
								<strong class="card-title">Group Members</strong>
								<a href="?groups" type="button" class="btn btn-outline-primary float-right mb-1"><i class="fa fa-arrow-circle-left"></i>  Back To Groups</a>
							</div>

							<div class="card-body">
								<table id="bootstrap-data-table" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>ID</th>
											<th>Name</th>
											<th>Email</th>
											<th>Phone Number</th>
											<th>joinDate</th>
											<th>Registered</th>
											<th>-</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$_group = new group();
										$students = $_group->getMembers($_GET['id']);
										foreach ($students as $student) { ?>
											<tr>
												<td><strong><?php echo $student->id ?></strong></td>
												<td><?php echo $student->name ?></td>
												<td><?php echo $student->email ?></td>
												<td><?php echo $student->phone ?></td>
												<td><?php echo $student->joinDate ?></td>
												<td><?php echo ($student->registered == 1?'<span class="badge badge-success">Yes</span>':'<span class="badge badge-danger">No</span>') ?></td>
												<td>
													<button type="button" class="btn btn-outline-danger btn-block"
														onclick="javascript: if (confirm('Are you sure you want to Remove This Student?'))
														{window.location.href='app/controller/group.inc.php?deleteStudent=<?php echo $student->id ?>&group=<?php echo $_GET['id'] ?>'}">
														<i class="fa fa-trash"></i>Remove</button>
												</td>
											</tr>
											<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php }else{ ?>
            <div class="card">
              <div class="card-header">
                <strong class="card-title">Groups</strong>
                <button type="button" class="btn btn-outline-primary float-right mb-1" data-toggle="modal" data-target="#addnewgroup"><i class="fa fa-plus"></i>Add New Group</button>
              </div>

              <div class="card-body">
                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Members</th>
                      <th>-</th>
                      <th>-</th>
                      <th>-</th>
                      <th>-</th>
                      <th>-</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
										foreach ($allGroups as $group) { ?>
                      <tr>
                        <td>
                          <?php echo $group->name ?>
                        </td>
                        <td><a href="#" class="badge badge-primary"><?php echo $group->members ?></a></td>
                        <td class="text-center">
                          <a type="button" class="btn btn-outline-info" href="?results&groupID=<?php echo $group->id ?>"><i class="fa fa-pie-chart"></i> Results</a>
                        </td>
                        <td class="text-center">
                          <a type="button" class="btn btn-outline-success" href="?groups&invitations&id=<?php echo $group->id ?>"><i class="fa fa-envelope"></i> Invitations</a>
                        </td>
                        <td class="text-center">
                          <a type="button" class="btn btn-outline-secondary" href="?groups&viewMembers&id=<?php echo $group->id ?>"><i class="fa fa-eye"></i> Members</a>
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-outline-danger" <?php echo ($group->members == 0)? '':'disabled' ?> onclick="javascript: if (confirm('Are you sure you want to delete this Group?'))
														{ window.location.href='app/controller/group.inc.php?deleteGroup=<?php echo $group->id ?>';}">
														<i class="fa fa-trash"></i> Delete
													</button>
                        </td>
                        <td class="text-center">
                          <button type="button" data-toggle="modal" data-target="#editgroup" data-gname="<?php echo $group->name ?>" data-gid="<?php echo $group->id ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-magic"></i>Update</button>
                        </td>
											</tr>
											<?php if($group->assignedTest){ ?>
											<tr>
												<td colspan="10"  style="padding-left:20px">
													<div>
													<table class="table table-borderless">
														<tbody>
															<tr>
																	<td>
																		<?php echo (($group->isActive)? '<span class="badge badge-success">Opened':'<span class="badge badge-danger">Closed') ?></span>
																	</td>
																	<td>
																		<?php echo (($group->assignedTest) ? ('<a href="?tests=view&id='. $group->testID .'" class="badge black-link">' . $group->assignedTest . '</a>') : '') ?>
																	</td>
																	<td>
																		<?php
																		if ($group->endTime){ ?>
																		<span class="badge"><?php echo date('d/m h:i A', strtotime($group->startTime)) ?> </span> <i class="fa fa-arrow-right" aria-hidden="true"></i>
																		<span class="badge"><?php echo date('d/m h:i A', strtotime($group->endTime)) ?></span>
																	<?php }else{ ?>
																		<span class="badge">No Time Limit</span>
																	<?php } ?>
																	</td>
																	<td>
																		<span class="badge"><?php echo (($group->duration)? ($group->duration . ' Minutes') :'No Time Limit') ?></span>
																	</td>
																	<td>
																		<button type="button" data-gid="<?php echo $group->id ?>" class="btn btn-danger deleteAssignedTest">Remove</button>
																	</td>

															</tr>


														</tbody>
													</table>
													</div>
											</td>
										</tr>
									<?php }} ?>

                  </tbody>
                </table>
              </div>
            </div>
						<div class="modal fade" id="addnewgroup" tabindex="-1" role="dialog" aria-labelledby="addnewgroupLabel" aria-hidden="true">
				      <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
				        <div class="modal-content">
				          <div class="modal-header">
				            <h5 class="modal-title" id="addnewgroupLabel">Add New Group</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				              <span aria-hidden="true">&times;</span>
				            </button>
				          </div>
				          <div class="modal-body">
				            <div class="card-body card-block">
				              <form action="app/controller/group.inc.php?addGroup" method="post" id="addnewgroupForm">
				                <div class="form-group">
				                  <input type="text" name="groupName" placeholder="Enter Group Name.." class="form-control" required>
				                </div>
				              </form>
				            </div>
				          </div>
				          <div class="modal-footer">
				            <button type="submit" form="addnewgroupForm" class="btn btn-primary">
				              <i class="fa fa-dot-plus"></i> Add
				            </button>
				          </div>
				        </div>
				      </div>
				    </div>

				    <div class="modal fade" id="editgroup" tabindex="-1" role="dialog" aria-labelledby="editgroupLabel" aria-hidden="true">
				      <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
				        <div class="modal-content">
				          <div class="modal-header">
				            <h5 class="modal-title" id="editgroupLabel">Edit Group</h5>
				            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				              <span aria-hidden="true">&times;</span>
				            </button>
				          </div>
				          <div class="modal-body">
				                <form action="app/controller/group.inc.php?editGroup" id="editGroupForm" method="post">
				                  <input type="hidden" name="id" required>
				                  <div class="form-group">
				                    <input type="text" name="groupName" placeholder="Enter Group Name.." class="form-control" required>
				                  </div>

				                </form>

				          </div>
									<div class="modal-footer text-center">
										<button type="submit" form="editGroupForm" class="btn btn-primary btn-sm">
											<i class="fa fa-dot-circle-o"></i> Submit
										</button>
									</div>
				        </div>
				      </div>
				    </div>

						<div class="modal fade" id="updateAssignedTest" tabindex="-1" role="dialog" aria-labelledby="updateAssignedTestLabel" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-centered " role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="updateAssignedTestLabel">Update Assigned Test</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <div class="modal-body">
						        <form action="app/controller/group.inc.php?updateAssignedTest" id="updateAssignedTestForm" method="post">
											<input type="hidden" name="testID">
											<input type="hidden" name="groupID">
						          <div class="form-group">
						            <label for="startTime" class="col-form-label">Start Time:</label>
												<input type="datetime-local" class="form-control" name="startTime">
						          </div>
						          <div class="form-group">
						            <label for="endTime" class="col-form-label">End Time:</label>
												<input type="datetime-local" class="form-control" name="endTime">
						          </div>
						          <div class="form-group">
						            <label for="duration" class="col-form-label">Duration:</label>
												<input type="number" class="form-control" name="duration" min="1">
						          </div>
						          <div class="form-group">
						            <label for="recipient-name" class="col-form-label">End Time:</label>
												<div class="radio icheck-info">
														<input type="radio" id="sh1" name="showAnswers" value="2" checked/>
														<label for="sh1">Show Directly After Submit</label>
												</div>
												<div class="radio icheck-info">
														<input type="radio" id="sh2" name="showAnswers" value="1" />
														<label for="sh2">Show After Test Session</label>
												</div>
												<div class="radio icheck-info">
														<input type="radio" id="sh3" name="showAnswers" value="0"/>
														<label for="sh3">Don't Show Answers</label>
												</div>
						          </div>
						        </form>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						        <button type="submit" form="updateAssignedTestForm" class="btn btn-primary">Update</button>
						      </div>
						    </div>
						  </div>
						</div>
						<?php } ?>
        </div>
    </div>
  </div>
  </div>
  <?php
		define('ContainsDatatables', true);
		require_once 'footer.php';
		?>
