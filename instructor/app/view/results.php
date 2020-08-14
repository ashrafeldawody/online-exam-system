<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
$res = new result;

		if(isset($_GET['needsReview'])){
			$review = $res->questionsNeedsReview();
			?>
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<strong class="card-title">Essay Answers Needs Review</strong>
						<a href="?results" type="button" class="btn btn-outline-primary float-right mb-1"><i class="fa fa-arrow-left"></i> Return To Results</a>
					</div>

					<div class="card-body">
						<table id="bootstrap-data-table" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Student Name</th>
									<th>Question</th>
									<th>Student Answer</th>
									<th>-</th>
									<th>-</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($review as $question){?>
									<tr>
										<td><i class="fa fa-user"></i>
											<?php echo $question->StudentName ?>
										</td>
										<td>
											<?php echo $question->question ?>
										</td>
										<td>
											<?php echo $question->textAnswer ?>
										</td>
										<td>
											<form action="app/controller/result.inc.php?action=approveAnswer" method="POST" class="text-center">
												<input type="hidden" class="form-control" name="ansID" value="<?php echo $question->id ?>">
												<div class="form-row align-items-center" style="min-width:250px">
											    <div class="col-8 my-1">
											      <div class="input-group">
											        <input type="number" class="form-control" name="points" placeholder="Max(<?php echo $question->points ?>) Points" max="<?php echo $question->points ?>" min="1">
											     </div>
											    </div>
											      <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
											  </div>
											</form>
										</td>
										<td><a href="app/controller/result.inc.php?action=rejectAnswer&id=<?php echo $question->id ?>" class="btn btn-danger"><i class="fa fa-close"></i>Reject Answer</a></td>
									</tr>
									<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php }elseif(isset($_GET['unsubmitted'])){
			$res = new result;
			$results = $res->getUnsubmitted();
			 ?>
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<strong class="card-title">Unsubmitted Results</strong>
						<a href="?results&needsReview" type="button" class="btn btn-outline-primary float-right"><i class="fa fa-left-arrow"></i>All Results</a>
					</div>
					<div class="card-body">
						<table id="ResultsTable" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Student</th>
									<th>Student ID</th>
									<th>Test</th>
									<th>Date</th>
									<th>IP Address</th>
									<th>Host Name</th>
									<th>Grade</th>
									<th>Percent</th>
									<th>-</th>
								</tr>
							</thead>
							<tbody>
					<?php foreach($results as $result){
								$percent = round(($result->TestDegree != 0)?(($result->FinalGrade / $result->TestDegree) * 100):0);
					?>
									<tr>
										<td><i class="fa fa-user"></i>
											<?php echo $result->studentName ?>
										</td>
										<td>
											<?php echo $result->studentID ?>
										</td>
										<td>
											<?php echo $result->testName ?>
										</td>
										<td>
											<?php echo $result->endTime ?>
										</td>
										<td>
											<?php echo $result->ipaddr ?>
										</td>
										<td>
											<?php echo $result->hostname ?>
										</td>
										<td><span class="badge badge-<?php echo (($percent < 50) ? 'danger':'success') ?>"><?php echo $result->FinalGrade . ' / ' . $result->TestDegree ?></span></td>
										<td>
											<?php echo (($percent < 50) ? ('<span class="badge badge-danger">' . $percent . '%') : ('<span class="badge badge-success">' . $percent . '%')) ?>
										</td>
										<td><a href="?results&id=<?php echo $result->id ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-info-circle"></i>Details</a></td>
									</tr>
									<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php }elseif(isset($_GET['id'])){
			if(is_numeric($_GET['id'])){
			$result = $res->getByID($_GET['id']);
			if(empty($result)){
				header('Location: ?results');
				exit;
			}
			$precent = ($result->TestDegree > 0)?(($result->FinalGrade / $result->TestDegree) * 100):0;
		}else{
			header('Location: ?results');
			exit;
		}
		$answers = $res->getResultAnswers($result->id);
?>
    <div class="animated fadeIn">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <strong class="card-title">Result #<?php echo $result->id ?></strong>
						<button type="button" onclick="window.print();" class="btn btn-outline-primary float-right mb-1 non-printable"><i class="fa fa-print"></i> Print</button>

          </div>
          <div class="card">
            <div class="card-body" id="resultDetails">
							<?php
						if($result->Questions == 0){
							echo '<div class="alert alert-danger text-center" role="alert">
											This Test Was Not Submitted
										</div>'; }?>
              <div class="row">
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Student:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo $result->studentName ?>
                      <a class="fa fa-info-circle showStudentData" data-id="<?php echo $result->studentID ?>" data-email="<?php echo $result->studentMail ?>" data-name="<?php echo $result->studentName ?>" data-phone="<?php echo $result->studentPhone ?>"></a>
                  </label>
                </div>
				<div class="col-6">
					<label class="control-label mb-1"><strong>Test:  </strong></label>
					<label class="control-label mb-1">
						<?php echo $result->testName ?>
					</label>
				</div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Date:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo date('d-m-Y', strtotime($result->startTime)) ?>
                  </label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Duration:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo $result->resultDuration ?> Minutes</label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Started:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo date('h:i A', strtotime($result->startTime)) ?>
                  </label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Ended:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo date('h:i A', strtotime($result->endTime)) ?>
                  </label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>IP Address:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo $result->ipaddr ?>
                  </label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Host Name:  </strong></label>
                  <label class="control-label mb-1">
                    <?php echo $result->hostname ?>
                  </label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>No. Of Questions: </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-primary"><?php echo $result->Questions ?></span></label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Test Grade:  </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-primary"><?php echo $result->TestDegree ?></span></label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Correct Questions:  </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-success"><?php echo $result->RightQuestions ?></span></label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Wrong Questions:  </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-danger"><?php echo $result->WrongQuestions ?></span></label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Grade<small> (Points)</small>:  </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-<?php echo ($result->passPercent < $precent)? 'success':'danger' ?>"><?php echo $result->FinalGrade .' / '.$result->TestDegree ?></span></label>
                </div>
                <div class="col-6">
                  <label class="control-label mb-1"><strong>Grade<small> (Precent)</small>:  </strong></label>
                  <label class="control-label mb-1"><span class="badge badge-<?php echo ($result->passPercent < $precent)? 'success':'danger' ?>"><?php echo round($precent) ?>%</span></label>
                </div>
              </div>
							<?php
							if($result->Questions != 0){ ?>
              <div class="row non-printable">
                <div class="col-6">
                  <div class="pieID--operations pie-chart--wrapper">
                    <h2>Answers</h2>
                    <div class="pie-chart">
                      <div class="pie-chart__pie"></div>
                      <ul class="pie-chart__legend">
                        <li><em>Correct</em><span><?php echo $result->RightQuestions ?></span></li>
                        <li><em>Wrong</em><span><?php echo $result->WrongQuestions ?></span></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-6 mt-4 gradeCircule">
                  <h2 class="text-center">Grade</h2>
                  <div id="progress" data-dimension="300" data-text="<?php echo round($precent) ?>%" data-fontsize="36" data-percent="<?php echo round($precent) ?>" data-fgcolor="<?php echo ($result->passPercent < $precent)? '#28a745':'#dc3545' ?>" data-bgcolor="#eee"
                  data-width="15" data-bordersize="15" data-animationstep="2"></div>
                </div>
              </div>
						<?php } ?>
            </div>
          </div>
          <div class="card-header">
            <strong class="card-title">Result Answers</strong>
          </div>
          <div class="card-body">
            <?php
						$types = [0 =>'Multiple Choice',1 =>'True/False',2 =>'Complete',3 =>'Multiple Select',4 =>'Matching',5 =>'Essay'];
						$I = 0;
						foreach($answers as $answer){
							$I++; ?>
              <div class="container">
                <div class="card m-4">
                  <div class="card-body">
										<?php
										if($answer->points == 0)
										echo '<span class="float-right ml-3 badge badge-danger">0 Points</span>';
										elseif($answer->points > 0)
										echo '<span class="float-right ml-3 badge badge-success">' .$answer->points. ' Points</span>';
										else
										echo '<span class="float-right ml-3 badge badge-warning">Not Yet Reviewed</span>';
										 ?>
										<span class="float-right ml-3 badge badge-secondary">
											<?php echo $types[$answer->type] ?>
										</span>
                    <blockquote class="blockquote">
                      <?php echo '<span class="badge badge-secondary">' .$I. ') </span>' .$answer->question ?>
                    </blockquote>
                    <hr>
                    <?php if($answer->type == 4){ ?>
                      <div class="row mt-4">
                        <div class="col-6 text-center" style="font-size:1.2rem">
                          <h5 class="ml-3 text-left">Student Answers</h5>
                          <?php $givenAnswers = $res->getGivenAnswers($result->id,$answer->id);?>
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">Answer</th>
                                  <th scope="col">Match</th>
                                  <th scope="col">Points</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($givenAnswers as $ans)
															echo '<tr class="table-'. (($ans->isCorrect) ? 'success':'danger') .'"><th>' . $ans->answer . '</th><th>' . $ans->textAnswer . '</th><th>+' . $ans->points . '</th></tr>'; ?>
                              </tbody>
                            </table>
                        </div>
                        <div class="col-6 text-center" style="font-size:1.2rem">
                          <h5 class="ml-3 text-left">Correct Answers</h5>
                          <?php $correctAnswers = $res->getCorrectAnswers($answer->id);?>
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th scope="col">Answer</th>
                                  <th scope="col">Correct Match</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($correctAnswers as $ans)
															echo '<tr class="table-success"><th>' . $ans->answer . '</th><th>' . $ans->matchAnswer . '</th></tr>'; ?>
                              </tbody>
                            </table>
                        </div>
                      </div>
                      <?php }elseif($answer->type == 0 || $answer->type == 3){ ?>
                        <div class="mt-5 ml-5 mr-5">
                          <?php $givenAnswers = $res->getGivenAnswers($result->id,$answer->id);
										$correctAnswers = $res->getCorrectAnswers($answer->id);?>
                            <h4>Student Answer:</h4>
                            <div class="row">
                              <?php foreach ($givenAnswers as $ans)
												echo '<div class="col-5 p-3 '. (($ans->isCorrect)?'correctAnswer':'wrongAnswer') .' mr-5">'. $ans->answer .'</div>'; ?>
                            </div>
                            <hr>
														<?php $rand = rand(10,100); ?>
                            <a class="mt-5" data-toggle="collapse" href="#mcqCorrect<?php echo $rand ?>" role="button" aria-expanded="false" aria-controls="collapseExample">View Correct Answer</a>
                            <div class="collapse" id="mcqCorrect<?php echo $rand ?>">
                              <div class="mt-4">
                                <div class="row">
                                  <?php foreach ($correctAnswers as $ans)
														echo '<div class="col-5 p-3 correctAnswer mr-5">'. $ans->answer .'</div>'; ?>
                                </div>
                              </div>
                            </div>
                        </div>
                        <?php }elseif($answer->type == 1){
								$givenAnswers = $res->getGivenAnswers($result->id,$answer->id); ?>
                          <div class="row">
                            <div class="col-6">
                              Student Answer: <span class="badge badge-primary"><?php echo (($givenAnswers[0]->isTrue)?'True':'False'); ?></span>
                            </div>
                            <div class="col-6">
                              Accepted Answer: <span class="badge badge-success"><?php echo (($answer->isTrue)?'True':'False');?></span>
                            </div>
                          </div>
                          <?php }elseif($answer->type == 2){
												$givenAnswers = $res->getGivenAnswers($result->id,$answer->id);
												$correctAnswers = $res->getCorrectAnswers($answer->id);?>
                            <div class="row">
                              <div class="col-6">
                                Student Answer: <span class="badge badge-primary"><?php echo $givenAnswers[0]->textAnswer ?></span>
                              </div>
                              <div class="col-6">
                                Accepted Answer:
                                <?php foreach($correctAnswers as $a) echo '<span class="badge badge-primary ml-3">' . $a->answer.'</span>'; ?>
                              </div>
                            </div>
                            <?php }elseif($answer->type == 5){
								$givenAnswers = $res->getGivenAnswers($result->id,$answer->id); ?>
                              <div class="form-group">
                                <label for="essy">Student Answer</label>
                                <textarea class="form-control" id="essy" rows="4" disabled><?php echo $givenAnswers[0]->textAnswer; ?></textarea>
                              </div>
                              <?php } ?>

                  </div>
                </div>
              </div>




              <?php } ?>
          </div>
        </div>
      </div>

    </div>
    <?php
		}else{
			$_admin = new admin;
			$_student = new student;
			$res = new result;
			if(isset($_GET['code'])){
				$results = $res->getAllByLink($_GET['code']);
			}elseif(isset($_GET['studentID']) and $_SESSION['mydata']->isAdmin){
				$results = $_admin->getStudentResults($_GET['studentID']);
			}elseif(isset($_GET['studentID']) and !$_SESSION['mydata']->isAdmin){
				$results = $_student->getStudentResults($_GET['studentID']);
			}elseif(isset($_GET['testID']) and !$_SESSION['mydata']->isAdmin){
				$results = $res->getTestResults($_GET['testID']);
			}elseif(isset($_GET['groupID']) and !$_SESSION['mydata']->isAdmin){
				$results = $res->getGroupResults($_GET['groupID']);
			}else{
				$results = $res->getAll();
			} ?>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title"><?php echo ((isset($_GET['studentID']) && $results)?('Student ['. $results[0]->studentName) . ']':(isset($_GET['code'])?('Code ' . $_GET['code']):'')) ?> Results</strong>
							<?php if(!$_SESSION['mydata']->isAdmin){ ?>
							<a href="?results&unsubmitted" type="button" class="btn btn-outline-secondary float-right m-1"><i class="fa fa-close"></i>Unsubmitted Results</a>
							<a href="?results&needsReview" type="button" class="btn btn-outline-primary float-right m-1"><i class="fa fa-check-square-o"></i>Answers Needs Review</a>
						<?php } ?>
						</div>
            <div class="card-body">
              <table id="ResultsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Test</th>
                    <th>Date</th>
                    <th>IP Address</th>
                    <th>Host Name</th>
                    <th>Grade</th>
                    <th>Percent</th>
                    <th>-</th>
                  </tr>
                </thead>
                <tbody>
            <?php foreach($results as $result){
									$percent = round(($result->TestDegree != 0)?(($result->FinalGrade / $result->TestDegree) * 100):0);
						?>
                    <tr>
                      <td><i class="fa fa-user"></i>
                        <?php echo $result->studentName ?>
                      </td>
                      <td>
                        <?php echo $result->studentID ?>
                      </td>
                      <td>
                        <?php echo $result->testName ?>
                      </td>
                      <td>
                        <?php echo $result->endTime ?>
                      </td>
                      <td>
                        <?php echo $result->ipaddr ?>
                      </td>
                      <td>
                        <?php echo $result->hostname ?>
                      </td>
                      <td><span class="badge badge-<?php echo (($percent < 50) ? 'danger':'success') ?>"><?php echo $result->FinalGrade . ' / ' . $result->TestDegree ?></span></td>
                      <td>
                        <?php echo (($percent < 50) ? ('<span class="badge badge-danger">' . $percent . '%') : ('<span class="badge badge-success">' . $percent . '%')) ?>
                      </td>
                      <td><a href="?results&id=<?php echo $result->id ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-info-circle"></i>Details</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php } ?>
          </div>
          <?php
define('ContainsCharts', true);
define('ContainsDatatables', true);
require_once 'footer.php';
?>
