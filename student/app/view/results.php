<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
$res = new test;
	if(isset($_GET['id'])){
			if($_GET['id'] == 'Last'){
				$result = $res->getFinishedResult();
			}else{
				$result = $res->getMyResult($_GET['id']);
			}
			if(empty($result)){
				header('Location: ?results');
				exit;
			}
			$precent = round(($result->TestDegree != 0)?(($result->FinalGrade / $result->TestDegree) * 100):0);
			$answers = $res->getResultAnswers($result->id);
?>
<body class="bg-light">

  <div class="container mt-3">
          <div class="col-md-12">
						<?php
						if ($result->releaseResult == 0){
							echo '<div class="alert alert-warning text-center" role="alert">
								This Result was not yet released
							</div>';
						}else{ ?>
              <div class="card">
                  <div class="card-header">
                      <strong class="card-title">Result - <?php echo $result->testName ?></strong>
											<button type="button" class="btn btn-outline-primary float-right mb-1" onclick="window.print();return false;">Print Result</button>
                  </div>
							<div class="card">
								<div class="card-body">
									<?php
									if($precent >= $result->passPercent){
										echo '<div class="alert alert-success text-center" role="alert">
											Congratulation.. Your Have Passed The Test<br>
											Your Result is: ' . $precent . '%
										</div>';
									}elseif($result->Questions == 0){
										echo '<div class="alert alert-danger text-center" role="alert">
														This Test Was Not Submitted
													</div>';
									}else{
										echo '<div class="alert alert-danger text-center" role="alert">
														Unfortunately.. Your did\'t Pass The Test<br>
														Your Result is: ' . $precent . '%
													</div>';
									} ?>
									<div class="row">
										<div class="col-6">
												<label class="control-label mb-1"><strong>Test:  </strong></label>
												<label class="control-label mb-1"><?php echo $result->testName ?></label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Instructor:  </strong></label>
											<label class="control-label mb-1"><?php echo $result->Instructor ?></label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Date:  </strong></label>
											<label class="control-label mb-1"><?php echo date('d-m-Y', strtotime($result->startTime)) ?></label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Duration:  </strong></label>
											<label class="control-label mb-1"><?php echo $result->resultDuration ?> Minutes</label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Started:  </strong></label>
											<label class="control-label mb-1"><?php echo date('h:i A', strtotime($result->startTime)) ?></label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Ended:  </strong></label>
											<label class="control-label mb-1"><?php echo date('h:i A', strtotime($result->endTime)) ?></label>
										</div>


										<div class="col-6">
											<label class="control-label mb-1"><strong>No. Of Questions:  </strong></label>
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
											<label class="control-label mb-1"><strong>Final<small>Grade</small>:  </strong></label>
											<label class="control-label mb-1"><span class="badge badge-<?php echo ($result->passPercent < $precent)? 'success':'danger' ?>"><?php echo $result->FinalGrade .' / '.$result->TestDegree ?></span></label>
										</div>
										<div class="col-6">
											<label class="control-label mb-1"><strong>Results<small>(Precent)</small>:  </strong></label>
											<label class="control-label mb-1"><span class="badge badge-<?php echo ($result->passPercent < $precent)? 'success':'danger' ?>"><?php echo $precent ?>%</span></label>
										</div>
									</div>
								</div>
              </div>
							<div class="card-header">
									<strong class="card-title">Questions</strong>
							</div>
							<div class="card-body">
							<?php
							$viewAnswers = $res->canViewResults($result->id);
							if(($viewAnswers == 0) or (($viewAnswers == 1) and (strtotime($result->testEnd) < strtotime("now")))){
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
											<h5 class="ml-3 text-left">Your Answers</h5>
											<?php $givenAnswers = $res->getGivenAnswers($result->id,$answer->id);?>
											<table class="table table-hover">
													<thead>
														<tr>
															<th scope="col">Answer</th>
															<th scope="col">Correct Match</th>
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
											<h4>Your Answer:</h4>
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
												Your Answer: <span class="badge badge-primary"><?php echo (($givenAnswers[0]->isTrue)?'True':'False'); ?></span>
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
												Your Answer: <span class="badge badge-primary"><?php echo $givenAnswers[0]->textAnswer ?></span>
											</div>
											<div class="col-6">
												Accepted Answer: <?php foreach($correctAnswers as $a) echo '<span class="badge badge-primary ml-3">' . $a->answer.'</span>'; ?>
											</div>
											</div>
								<?php }elseif($answer->type == 5){
 									$givenAnswers = $res->getGivenAnswers($result->id,$answer->id); ?>
									<div class="form-group">
								    <label for="essy">Your Answer</label>
								    <textarea class="form-control" id="essy" rows="4" disabled><?php echo $givenAnswers[0]->textAnswer; ?></textarea>
								  </div>
								<?php } ?>

									</div>
								</div>
								</div>
							<?php }}elseif($viewAnswers == 2){
								echo '<div class="alert alert-warning text-center" role="alert"><p>You can\'t view the answers of this test</p></div>';

						}else{
							  echo '<div class="alert alert-info text-center" role="alert"><p>Your answers will be available in '. date('d/m/Y h:i A', strtotime($result->testEnd)) .'</p></div>';
							} ?>
						</div>
            </div>

					<?php } ?>
        </div>
</div>
<?php
}else{

			?>
        <div class="container mt-3">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Results</strong>
                            </div>
														<?php
														$results = $res->getMyResults();
														?>
                            <div class="card-body">
                                <table class="table table-striped table-bordered resultTable">
                                    <thead>
                                        <tr>
																					<th>Test</th>
                                          <th>Date</th>
                                          <th>Result</th>
                                          <th>-</th>
                                        </tr>
                                    </thead>
                                    <tbody>
																			<?php foreach($results as $result){
																				$precent = round(($result->TestDegree != 0)?(($result->FinalGrade / $result->TestDegree) * 100):0);
																				?>
                                        <tr>
																						<th><?php echo $result->testName ?></th>
                                            <th><?php echo $result->endTime ?></th>
                                            <td><?php echo ($result->releaseResult == 1) ? '<span class="badge badge-'. (($precent <= 50) ? "danger" : "success"). '">'. $precent .'%</span>':'<span class="badge badge-warning">Under Review</span>'; ?></td>
                                            <td>
																							<?php if ($result->releaseResult == 1){ ?>
																							<a href="?results&id=<?php echo $result->id ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-info-circle"></i>Details</a>
																						<?php } ?>
																						</td>
                                        </tr>
																			<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php } ?>
    </div>
	<?php
define('ContainsDatatables', true);
require_once 'footer.php';
?>
