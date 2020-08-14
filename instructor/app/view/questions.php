<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'navbar.php';
$question = new question;
?>
  <div class="content mt-3">
    <?php
			if($_GET['questions'] == "add") {
				?>
      <div class="mt-3 text-center">
        <div class="col-lg-6" style="max-width:100%">
					<?php
					if(isset($_SESSION['error']))
					foreach($_SESSION['error'] as $err){
					echo '<div class="alert alert-danger alert-dismissible fade show">
					<span class="badge badge-pill badge-danger">Failed</span>'. $err . '</div>';}
					unset($_SESSION['error']);
					if (isset($_SESSION['info']))
					foreach($_SESSION['info'] as $info){
						echo '<div class="alert alert-success alert-dismissible fade show">
						<span class="badge badge-pill badge-success">Success</span>'. $info . '</div>';}

						unset($_SESSION['info']);
						?>
          <div class="card">
            <div class="card-header">
              Add New <strong>Question</strong>
            </div>
            <div class="card-body card-block">
              <form action="app/controller/question.inc.php?addQuestion" method="post" enctype="multipart/form-data" class="form-horizontal">
								<div class="row form-group">
									<div class="col col-md-3">
										<label for="select" class=" form-control-label">Topic</label>
									</div>
									<?php
										 	$topic = (isset($_GET['topic'])?$_GET['topic']:0);
									 ?>

									<div class="col-12 col-md-4">
											<select name="Course" class="form-control" required>
												<?php
												$cat = new course;
												$parents = $cat->getAllParents();
												foreach ($parents as $parent) {
													echo '<optgroup label="'. $parent->name .'">';
													$childs = $cat->getAllChilds($parent->id);
													foreach ($childs as $child) {
																echo '<option value="'. $child->id .'"'. (($child->id == $topic)?'selected':'') .'>'. $child->name .'</option>';
													}
													echo '</optgroup>';
												}
												?>
										</select>
									</div>
								</div>
								<div class="row form-group">
                  <div class="col col-md-3">
                    <label for="textarea-input" class=" form-control-label">Question Text</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <textarea name="questionText" id="textarea-input" rows="4" placeholder="Write Your Question..." class="form-control summernote"></textarea>
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="type" class=" form-control-label">
                      <br>Question Type</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input type="hidden" name="qtype" id="qtype" value="0">
                    <ul class="nav nav-pills mb-3 text-center" id="type" role="tablist">
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='0';">
                        <a class="nav-link active black-link" data-toggle="pill" href="#MCQ" role="tab" aria-controls="pills-home" aria-selected="true"><img class="thumbnailimage" src="../style/images/mcq.png">
                          <br> Multiple Choise</a>
                      </li>
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='3';">
                        <a class="nav-link black-link" data-toggle="pill" href="#MSQ" role="tab" aria-controls="pills-home" aria-selected="true"><img class="thumbnailimage" src="../style/images/msq.png">
                          <br> Multiple Select</a>
                      </li>
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='1';">
                        <a class="nav-link black-link" data-toggle="pill" href="#TF" role="tab" aria-controls="pills-profile" aria-selected="false"><img class="thumbnailimage" src="../style/images/TF.png">
                          <br> True/False</a>
                      </li>
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='2';">
                        <a class="nav-link black-link" data-toggle="pill" href="#COMPLETE" role="tab" aria-controls="pills-contact" aria-selected="false"><img class="thumbnailimage" src="../style/images/complete.png">
                          <br> Complete</a>
                      </li>
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='4';">
                        <a class="nav-link matchingTab black-link" data-toggle="pill" href="#matching" role="tab" aria-controls="pills-contact" aria-selected="false"><img class="thumbnailimage" src="../style/images/matching.png">
                          <br> Matching</a>
                      </li>
                      <li class="nav-item" onclick="javascript: document.getElementById('qtype').value='5';">
                        <a class="nav-link black-link" data-toggle="pill" href="#essay" role="tab" aria-controls="pills-contact" aria-selected="false"><img class="thumbnailimage" src="../style/images/essay.png">
                          <br> Essay</a>
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade" id="essay" role="tabpanel" aria-labelledby="pills-home-tab">
										<p class="text-center">You will have to manually check the answer and decide to accept it or not</p>
										<hr>
									</div>
                  <div class="tab-pane fade show active" id="MCQ" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row form-group">
                      <div class="col col-md-3">
                        <label class="form-control-label">Answers</label>
                      </div>
                      <input type="hidden" id="MCQlastAnswer" value="0">
                      <ul class="col-12 col-md-9 text-left MCQchoiseslist" id="answers">
												<?php
												for($i = 1;$i <=4;$i++){
												 ?>
                        <li class="list-group-item">
                          <div class="row">
                          <div class="col-6">
	                          <div class="icheck-success mb-4">
	                            <input type="radio" id="MCQcheck<?php echo $i ?>" name="MCQanswer[<?php echo $i ?>][isCorrect]" value="1" class="mcqCheckInput">
	                            <label for="MCQcheck<?php echo $i ?>">Correct Answer</label>
	                          </div>
                          </div>
													<div class="col-lg-6">
														<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>
													</div>
													</div>
                          <textarea rows="2" placeholder="Answer <?php echo $i ?>..." name="MCQanswer[<?php echo $i ?>][answertext]" class="form-control mcqTextarea summernote"></textarea>
                          <br>
                        </li>
												<script type='text/javascript'>++document.getElementById("MCQlastAnswer").value;</script>
												<br>
<?php } ?>
                      </ul>
                    </div>
                    <div class="row form-group">
                      <div class="col col-md-3"></div>
                      <div class="col-12 col-md-9 text-right">
                        <button type="button" class="btn btn-info btn-lg btn-block" id="MCQaddChoise"><i class="fa fa-plus"></i> Add More Answers</button>
                      </div>
                    </div>
                  </div>
									<div class="tab-pane fade" id="MSQ" role="tabpanel">
                    <div class="row form-group">
                      <div class="col col-md-3">
                        <label class="form-control-label">Answers</label>
                      </div>
                      <input type="hidden" id="MSQlastAnswer" value="0">
                      <ul class="col-12 col-md-9 text-left MSQchoiseslist" id="answers">
												<?php
												for($i = 1;$i <=4;$i++){
												 ?>
                        <li class="list-group-item">
                          <div class="row">
                          <div class="col-6">
	                          <div class="icheck-success mb-4">
	                            <input type="checkbox" id="isrightcheck<?php echo $i ?>" name="MSQanswer[<?php echo $i ?>][isCorrect]" value="1" class="msqCheckInput">
	                            <label for="isrightcheck<?php echo $i ?>">Correct Answer</label>
	                          </div>
                          </div>
														<div class="col-lg-6">
															<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>
														</div>
                          </div>
                          <textarea rows="2" placeholder="Answer <?php echo $i ?>..." name="MSQanswer[<?php echo $i ?>][answertext]" class="form-control msqTextarea summernote"></textarea>
                          <br>
                        </li>
												<script type='text/javascript'>++document.getElementById("MSQlastAnswer").value;</script>
                        <br>
											<?php } ?>
                      </ul>
                    </div>
                    <div class="row form-group">
                      <div class="col col-md-3"></div>
                      <div class="col-12 col-md-9 text-right">
                        <button type="button" class="btn btn-info btn-lg btn-block" id="MSQaddChoise"><i class="fa fa-plus"></i> Add More Answers</button>
                      </div>
                    </div>
                  </div>
									<div class="tab-pane fade" id="matching" role="tabpanel">
                    <div class="row form-group">
                      <div class="col col-md-3">
                        <label class="form-control-label">Answers</label>
                      </div>
                      <input type="hidden" id="MSQlastAnswer" value="0">
                      <ul id="MatchingAnswers">
												<?php
												for($i = 'A';$i <='D';$i++){
												 ?>
                        <li class="list-group-item">
													<div class="row">
														<div class="col-4">
															<input type="text" class="form-control matchInp" placeholder="Clue <?php echo $i ?>" name="match[]">
														</div>
															<i class="fa fa-arrow-right mt-2" aria-hidden="true"></i>
														<div class="col-4">
															<input type="text" class="form-control matchAnswerInp" placeholder="Match <?php echo $i ?>" name="matchAnswer[]">
														</div>
														<div class="col-2">
															<input type="number" class="form-control" placeholder="Points" value="1" name="matchPoints[]">
														</div>
														<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>
													</div>
                        </li>
											<?php } ?>
                      </ul>
                    </div>
                    <div class="row form-group">
                      <div class="col col-md-3"></div>
                      <div class="col-12 col-md-9 text-right">
                        <button type="button" class="btn btn-info btn-lg btn-block" id="addMatch"><i class="fa fa-plus"></i> Add More Answers</button>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="TF" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row form-group">
                      <div class="col col-md-3">
                        <label class=" form-control-label">Answers</label>
                      </div>
                      <ul class="col-12 col-md-9 text-left" id="answers">
                        <li class="list-group-item">
                          <div class="radio icheck-peterriver">
                            <input type="radio" id="primary1" name="isTrue" value="1" Checked/>
                            <label for="primary1">Correct Answer</label>
                          </div>
                          <hr>
                          <input type="text" class="form-control" value="True" disabled>
                        </li>
                        <li class="list-group-item">
                          <div class="radio icheck-peterriver">
                            <input type="radio" id="primary2" name="isTrue" value="0" />
                            <label for="primary2">Correct Answer</label>
                          </div>
                          <hr>
                          <input type="text" class="form-control" value="False" disabled>
                        </li>
                      </ul>
                    </div>

                  </div>
                  <div class="tab-pane fade" id="COMPLETE" role="tabpanel" aria-labelledby="pills-contact-tab">
										<div class="row form-group">
										<div class="col col-md-3">
											<label class="form-control-label">Answers</label>
										</div>
                    <div class="col-12 col-md-9 text-left">
                      <input type="hidden" id="lastCompleteAnswer" value="2">
                      <div class="Completelist">
                        <div class="row form-group completeanswer">
                          <div class="col-12 col-md-9">
                            <input type="text" id="answer1" name="Canswer[0][answertext]" placeholder="Answer 1" class="form-control">
                          </div>
													<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>
                        </div>
                        <div class="row form-group completeanswer">
                          <div class="col-12 col-md-9">
                            <input type="text" id="answer2" name="Canswer[1][answertext]" placeholder="Answer 2" class="form-control">
                          </div>
													<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-12 col-md-9 text-right">
                          <button type="button" id="addComAnswer" class="btn btn-info btn-lg btn-block"><i class="fa fa-plus"></i> Add More Answers</button>
                        </div>
                      </div>
                    </div>
                    </div>

                  </div>
                </div>

								<div class="row form-group">
									<div class="col col-md-3">
										<label for="difficulty" class="form-control-label">Difficulty Level</label>
									</div>
									<div class="col-12 col-md-4">
											<select name="difficulty" id="difficulty" class="form-control" required>
													<option value="1">Easy</option>
													<option value="2">Moderate</option>
													<option value="3">Hard</option>
										</select>
									</div>
								</div>
                <br>
                <div class="row form-group points-group">
                  <div class="col col-md-3">
                    <label class=" form-control-label">Points</label>
                  </div>
                  <div class="col-12 col-md-2 text-center">
                    <input type="number" name="points" min="1" class="form-control" value="1" required>
                  </div>
                </div>
                <button type="submit" id="AddQuestion" class="btn btn-primary btn-lg btn-block"><i class="fa fa-plus"></i> Add Question</button>

              </form>
            </div>
          </div>
        </div>
      </div>


      <?php
		}elseif($_GET['questions'] == "import") {?>
			<div class="row m-0">
				<div class="col-lg-6">
			<div class="card">
			  <div class="card-header text-center">
			    <strong class="card-title">Import Questions To Database</strong>
			  </div>
			  <div class="card-body">
			      <form action="app/controller/question.inc.php?import" method="post" enctype="multipart/form-data">
			        <p>You Must Follow The Templete in order to import questions: <br>
								<a href="questionsTemplate.xlsx">Click To Download Template</a>
			        </p>
			        <div class="form-group">
			          <div class="custom-file">
			            <input type="file" class="custom-file-input" name="excel" id="customFile" ria-describedby="filenote" accept=".xlsx" required>
			            <label class="custom-file-label" for="customFile">Choose file</label>
			          </div>
			        </div>
			        <div class="form-group">
								<label for="course">Topic</label>
								<select name="course" id="course" class="form-control" required>
												<?php
										$cat = new course;
										$parents = $cat->getAllParents();
										foreach ($parents as $parent) {
										echo '<optgroup label="'. $parent->name .'">';
										$childs = $cat->getAllChilds($parent->id);
										foreach ($childs as $child) {
													echo '<option value="'. $child->id .'">'. $child->name .'('. $child->questions .')</option>';
										}
										echo '</optgroup>';
										}
										?>
								</select>
			        </div>
			        <br>
			        <button type="submit" class="btn btn-primary">Submit</button>
			      </form>

			  </div>
			</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
				  <div class="card-header text-center">
				    <strong class="card-title">Export Questions</strong>
				  </div>
				  <div class="card-body">
						<form action="app/controller/question.inc.php?export" method="post" enctype="multipart/form-data">
			        <div class="form-group">
								<label for="course">Topic</label>
								<select name="course" id="course" class="form-control" required>
												<?php
										$cat = new course;
										$parents = $cat->getAllParents();
										foreach ($parents as $parent) {
										echo '<optgroup label="'. $parent->name .'">';
										$childs = $cat->getAllChilds($parent->id);
										foreach ($childs as $child) {
													echo '<option value="'. $child->id .'">'. $child->name .'('. $child->questions .')</option>';
										}
										echo '</optgroup>';
										}
										?>
								</select>
			        </div>
			        <br>
			        <button type="submit" class="btn btn-primary">Submit</button>
			      </form>
				  </div>
				</div>
				</div>

			</div>

			</div>
			<?php
			}elseif(($_GET['questions'] == "edit") && isset($_GET['id'])) {
				$_question 	= $question->getByID($_GET['id']);
				$_answers 	= $question->getQuestionAnswers($_question->id);
				if(!isset($_question->id)){
					header('location: ?questions');
				}
				?>
        <div class="mt-3 text-center">
          <div class="col-lg-6" style="max-width:100%">
            <div class="card">
              <div class="card-header">
                Update <strong>Question</strong>
              </div>
              <div class="card-body card-block">

							<?php if($_question->inResults > 0){ ?>
								<div class="alert alert-warning">You can't Edit the question because it's already in a result<br> You can <a href="app/controller/question.inc.php?duplicateQuestion=<?php echo $_question->id; ?>">Duplicate Question</a></div>
							<?php }else{ ?>
                <form action="app/controller/question.inc.php?updateQuestion" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <input type="hidden" name="qid" value="<?php echo $_question->id?>" />

                  <div class="row form-group">
                    <div class="col col-md-3">
                      <label for="textarea-input" class=" form-control-label">Question</label>
                    </div>
                    <div class="col-lg-9">
                      <textarea name="questionText" id="textarea-input" rows="4" placeholder="Write Your Question?..." class="form-control summernote" required><?php echo $_question->question ?></textarea>
                    </div>
                  </div>
                  <div class="row form-group">
                    <input type="hidden" name="qtype" id="qtype" value="<?php echo $_question->type; ?>">
                    <div class="col col-md-3">
                      <label for="qtype" class=" form-control-label">Question Type</label>
                    </div>
                    <div class="col-12 col-md-9">
                      <label type="label" id="qtype" name="qtype">
                    <?php if ($_question->type == 0)
											echo '<span class="badge badge-primary">Multiple Choice</span>';
										elseif ($_question->type == 1)
											echo '<span class="badge badge-primary">True & False</span>';
										elseif ($_question->type == 2)
											echo '<span class="badge badge-primary">Complete</span>';
										elseif ($_question->type == 3)
											echo '<span class="badge badge-primary">Multiple Select</span>';
										elseif ($_question->type == 4)
											echo '<span class="badge badge-primary">Matching</span>';
										elseif ($_question->type == 5)
											echo '<span class="badge badge-primary">Essay</span>';?>
										</label>

                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col col-md-3">
                      <label for="answers" class=" form-control-label">Answers</label>
                    </div>
                    <input type="hidden" id="MCQlastAnswer" value="0">
                    <?php
								if ($_question->type == 0 || $_question->type == 3){
									?>
                  <div class="col-12 col-md-9 text-left">
                    <ul class="choiseslist" id="answers" style="padding:0">
                    <?php
										$i = 1;
										foreach ($_answers as $answer) {
										?>
                            <li class="list-group-item <?php echo ($answer->isCorrect == 1)? 'correctAnswer':'' ?>" id="MCQ<?php echo $i ?>">
                              <input type="hidden" name="Qanswer[<?php echo $i ?>][ansID]" value="<?php echo $answer->id ?>">
															<div class="row">
                              <div class="col-lg-6">
                                <div class="icheck-success">
                                  <input type="<?php echo (($_question->type == 0) ? 'radio' : 'checkbox') ?>" class="<?php echo (($_question->type == 0) ? 'mcqCheckInput' : 'msqCheckInput') ?>" id="isrightcheck<?php echo $i ?>"
																	name="Qanswer[<?php echo $i ?>][isCorrect]" value="1" <?php echo ($answer->isCorrect == 1)? 'Checked':'' ?>>
                                  <label for="isrightcheck<?php echo $i ?>">Correct Answer</label>
                                </div>
                              </div>
                              <div class="col-lg-6"><i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i></div>
															</div>
                              <textarea rows="2" placeholder="Answer <?php echo $i ?>..." name="Qanswer[<?php echo $i ?>][answertext]" class="form-control summernote"><?php echo $answer->answer ?></textarea>
                              <br>
                            </li>
                            <br>
                            <script type='text/javascript'>++document.getElementById("MCQlastAnswer").value;</script>
                            <?php $i++; } ?>
                        </ul>
                        <div class="row form-group">
                          <div class="col-12 col-md-9 text-right">
                            <button type="button" class="btn btn-info btn-lg btn-block" id="addChoise"><i class="fa fa-plus"></i> Add More Answers</button>
                          </div>
                        </div>
                      </div>
                  </div>
                  <?php
								}
								elseif ($_question->type == 2){ ?>
                    <input type="hidden" id="lastCompleteAnswer" value="0">
                    <div class="col-lg-8 text-left">
                      <div class="Completelist">
                        <?php $i = 1;
										foreach ($_answers as $answer) { ?>
                          <div class="row form-group completeanswer">
                            <div class="col-12 col-md-9">
                              <input type="text" id="answer<?php echo $i ?>" name="Canswer[<?php echo $i ?>][answertext]" placeholder="Answer <?php echo $i++ ?>" class="form-control" value="<?php echo $answer->answer ?>">
                            </div>
														<i class="fa fa-trash deleteAnswer text-danger mt-1" data-ansid="<?php echo $answer->id; ?>" style="font-size:1.6rem;cursor:pointer;" aria-hidden="true"></i>
                          </div>
                          <script type='text/javascript'>
                            document.getElementById("lastCompleteAnswer").value++;
                          </script>
                          <?php } ?>
                      </div>
                      <div class="row form-group">
                        <div class="col-lg-8"></div>
                        <div class="col-12 col-md-9 text-right">
                          <button type="button" id="addComAnswer" class="btn btn-info btn-lg btn-block"><i class="fa fa-plus"></i> Add More Answers</button>
                        </div>
                      </div>

                    </div>

                    <?php $i++; }
								elseif ($_question->type == 1){ ?>
                      <div class="col-12 col-md-9 text-left">
                        <div class="row form-group">
                          <ul class="col-12 col-md-9 text-left" id="answers">
                            <li class="list-group-item">
                              <div class="radio icheck-peterriver">
                                <input type="radio" id="primary1" name="isTrue" value="1" <?php echo ($_question->isTrue == 1)? 'Checked':'' ?> />
                                <label for="primary1">Correct Answer</label>
                              </div>
                              <hr>
                              <input type="text" class="form-control" value="True" disabled>
                            </li>
                            <li class="list-group-item">
                              <div class="radio icheck-peterriver">
                                <input type="radio" id="primary2" name="isTrue" value="0" <?php echo ($_question->isTrue == 0)? 'Checked':'' ?>/>
                                <label for="primary2">Correct Answer</label>
                              </div>
                              <hr>
                              <input type="text" class="form-control" value="False" disabled>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <?php
								}elseif ($_question->type == 4){ ?>
                      <div>
                        <div class="row form-group">
                          <ul id="MatchingAnswers">
														<?php $i = 0;
														 foreach ($_answers as $answer) {
															 $i++; ?>
                            <li class="list-group-item" id="matchListItem<?php echo $i ?>">
															<input type="hidden" value="<?php echo $answer->id; ?>" name="oldID[]">
															<div class="row">
																<div class="col-4">
																	<input type="text" class="form-control matchInp" value="<?php echo $answer->answer; ?>" name="match[]">
																</div>
																	<i class="fa fa-arrow-right mt-2" aria-hidden="true"></i>
																<div class="col-4">
																	<input type="text" class="form-control matchAnswerInp" value="<?php echo $answer->matchAnswer; ?>" name="matchAnswer[]">
																</div>
																<div class="col-2">
																	<input type="number" class="form-control" value="<?php echo $answer->points; ?>" name="matchPoints[]">
																</div>
																<i class="fa fa-trash deleteAnswer text-danger mt-1" data-ansid="<?php echo $answer->id; ?>" aria-hidden="true"></i>
															</div>
														</li>
													<?php } ?>
                          </ul>

                        </div>
												<div class="col-6" style="margin:0 auto">
													<button type="button" id="addMatch" class="btn btn-info btn-lg btn-block"><i class="fa fa-plus"></i> Add Answer</button>
												</div>
                      </div>
                      <?php
								} ?>
              </div>
              <div class="row form-group">

                <div class="col col-md-3">
                  <label for="select" class=" form-control-label">Course</label>
                </div>
                <div class="col-lg-3">
                            <select name="Course" class="form-control" required>
                <?php
					$cat = new course;
					$parents = $cat->getAllParents();
					foreach ($parents as $parent) {
						echo '<optgroup label="'. $parent->name .'">';
						$childs = $cat->getAllChilds($parent->id);
						foreach ($childs as $child) {
									echo '<option value="'. $child->id .'">'. $child->name .'('. $child->questions .')</option>';
						}
						echo '</optgroup>';
					}
					?>
                    </select>
                </div>
              </div>
							<div class="row form-group">
								<div class="col col-md-3">
									<label for="difficulty" class="form-control-label">Difficulty Level</label>
								</div>
								<div class="col-12 col-md-4">
										<select name="difficulty" id="difficulty" class="form-control" required>
												<option value="1" <?php echo (($_question->difficulty == 1)?'selected="selected"':'')?>>Easy</option>
												<option value="2" <?php echo (($_question->difficulty == 2)?'selected="selected"':'')?>>Moderate</option>
												<option value="3" <?php echo (($_question->difficulty == 3)?'selected="selected"':'')?>>Hard</option>
									</select>
								</div>
							</div>
              <div class="row form-group <?php echo ($_question->type == 4)?'d-none':'' ?>">
                <div class="col col-md-3">
                  <label class="form-control-label">Points</label>
                </div>
                <div class="col-12 col-md-2 text-center">
                  <input type="number" name="points" min="1" class="form-control" value="<?php echo $_question->points?>" required>
                </div>
              </div>
            </div>

          </div>
          <button type="submit" id="updateQuestion" class="btn btn-primary btn-lg btn-block"><i class="fa fa-plus"></i> Update Question</button>

          </form>
				<?php } ?>

        </div>
  </div>
  </div>
  </div>

  <?php
			}elseif($_GET['questions'] == "trash"){
				?>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title">Questions Trash</strong>
              <a href="?questions" type="button" class="btn btn-outline-primary float-right mb-1"><i class="fa fa-arrow-circle-left"></i>  Back To Questions Bank</a>

            </div>

            <div class="card-body">
              <table id="questionsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Question</th>
                    <th>Course</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
																	$Questionstrash = $question->getDeleted();
																	foreach ($Questionstrash as $question) {
																		echo '<tr>
																				<td>'. $question->question .'</td>
																				<td>'. $question->course .'</td>
																				<td><button type="button" onclick="window.location.href=\'app/controller/question.inc.php?restoreQuestion='. $question->id .'\'" class="btn btn-outline-primary btn-sm"><i class="fa fa-undo"></i> Restore</button></td>
																				<td><button type="button" onclick="window.location.href=\'app/controller/question.inc.php?PDeleteQuestion='. $question->id .'\'" class="btn btn-outline-danger btn-sm"' . (($question->used > 0)? "disabled":"") . '><i class="fa fa-trash"></i> Permanently Delete</button></td>
																		</tr>';
																	}?>
                </tbody>
              </table>
            </div>
          </div>




        </div>


    <!-- .animated -->
    <?php
			}elseif($_GET['questions'] == "view"){
				$qid = !empty($_GET['id'])? $_GET['id'] : null;
				if ($qid == null)
				echo("<script>location.href = '../instructor/?questions';</script>");
				$newQuestion = new question;
				$question = $newQuestion->getQuestionReport($qid);
				$answers  = $newQuestion->getQuestionAnswers($qid);
				$crt = $question->rightAnswers;
				$wrg = $question->wrongAnswers;
				$ttl = $question->inResults;
				$correct = (($ttl > 0) ? round(($crt / $ttl) * 100,0) :0);
				$wrong = (($ttl > 0) ? round(($wrg / $ttl) * 100,0):0);
				?>
      <div class="card">
        <div class="card-header">
          <strong>View</strong> Question
        </div>
        <div class="card-body card-block">
          <div class="row form-group">
            <div class="col col-md-3">
              <label for="questionText">Question</label>
            </div>
            <div class="col-12 col-md-9">
              <div class="bg-light"><?php echo $question->question; ?></div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3">
              <label>Course</label>
            </div>
            <div class="col-12 col-md-9">
              <input type="text" class="form-control" value="<?php echo $question->course; ?>" disabled>
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3">
              <label>Difficulty</label>
            </div>
            <div class="col-12 col-md-9">
              <?php echo (($question->difficulty == 1)?'<span class="badge badge-success">Easy</span>':(($question->difficulty == 2)?'<span class="badge badge-warning">Moderate</span>':'<span class="badge badge-danger">Hard</span>')) ?>
            </div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3">
              <label for="qTYPE">Question Type: </label>
            </div>
            <div class="col-12 col-md-9">
							<?php if ($question->type == 0)
								echo '<span class="badge badge-primary">Multiple Choice</span>';
							elseif ($question->type == 1)
								echo '<span class="badge badge-primary">True & False</span>';
							elseif ($question->type == 2)
								echo '<span class="badge badge-primary">Complete</span>';
							elseif ($question->type == 3)
								echo '<span class="badge badge-primary">Multiple Select</span>';
							elseif ($question->type == 4)
								echo '<span class="badge badge-primary">Matching</span>';
							elseif ($question->type == 5)
								echo '<span class="badge badge-primary">Essay</span>';?>
            </div>
          </div>
					<hr>
          <?php
				if($question->type == 1){
					echo '<div class="row form-group">
						<div class="col-5">
							<label class="form-control-label">Correct Answer: <span class="badge badge-'. (($question->isTrue == 1)?'success':'danger') .'" class="form-control" style="font-size:20">'. (($question->isTrue == 1)?'True':'False') .'</span></label>
						</div>
				</div>';
			}else{
				?>
				<div class="container bg-light">
					<h3 class="text-center"><span>Answers</span></h3>
					<div class="row">
						<?php

						if($question->type == 0 || $question->type == 3){
					foreach ($answers as $answer) {?>
							<div class="col-md-4">
								<div class="card m-2">
									<div class="card-body">
										<h6 class="card-text"><?php echo $answer->answer; ?></h6>
										<hr>
										<h4 class="card-title mb-1"><?php echo ($answer->isCorrect == '0')? '<span class="badge badge-danger">Wrong Answer</span>':'<span class="badge badge-success">Correct Answer</span>'; ?></h4>
									</div>
								</div>
							</div>
						<?php } }elseif($question->type == 2){
							foreach ($answers as $answer) { ?>
							<div class="col-md-4">
								<div class="card m-2">
									<div class="card-body">
										<h6 class="card-text"><?php echo $answer->answer; ?></h6>
										<hr>
										<h4 class="card-title mb-1"><span class="badge badge-success">Correct Answer</span></h4>
									</div>
								</div>
							</div>
				<?php	} }elseif ($question->type == 4){ ?>
							<ul style="margin:0 auto">
								<?php foreach ($answers as $answer) { ?>
                  <li class="list-group-item">
										<div class="row">
											<div class="col-4">
												<input type="text" class="form-control matchInp" disabled value="<?php echo $answer->answer; ?>">
											</div>
												<i class="fa fa-arrow-right mt-2" aria-hidden="true"></i>
											<div class="col-4">
												<input type="text" class="form-control matchAnswerInp" disabled value="<?php echo $answer->matchAnswer; ?>">
											</div>
											<div class="col-2">
												<input type="text" class="form-control" disabled value="<?php echo $answer->points; ?> Points">
											</div>
										</div>

									</li>
								<?php } ?>
								</ul>
				<?php	}	} ?>
			</div>
		</div>
				<hr>
				<div class="container  mt-5">
				<h4>Question Statistics</h4>
				<div class="row form-group">
					<div class="col col-md-3">
						<label>Answers Rate</label>
					</div>
					<div class="col-12 col-md-9">
						<div class="progress" style="height:1.5rem;font-size:1.5rem">
						  <div class="progress-bar bg-success" role="progressbar" style="width:<?php echo $correct?>%">
						    <?php echo $correct?>% Correct
						  </div>
						  <div class="progress-bar bg-danger" role="progressbar" style="width:<?php echo $wrong ?>%">
						    <?php echo $wrong ?>% Wrong
						  </div>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-5">
							<label class="form-control-label">Tests: <span class="badge badge-primary" class="form-control" style="font-size:20"><?php echo (empty($question->inTests) ? '0' : $question->inTests); ?></span></label>
					</div>
					<div class="col-5">
							<label class="form-control-label">Results: <span class="badge badge-info" class="form-control" style="font-size:20"><?php echo (empty($question->inResults) ? '0' : $question->inResults); ?></span></label>
					</div>
				</div>

        </div>
        </div>

        <div class="card-footer">
          <?php
			if(!(($question->inTests > 0) || ($question->inResults > 0))) { ?>
            <a type="button" href="app/controller/question.inc.php?deleteQuestion=<?php echo $question->id ?>" class="btn btn-danger  m-1" style="float:right"><i class="fa fa-trash"></i>&nbsp; Delete</a>
            <?php } ?>
              <button type="button" onclick="window.location.href='?questions=edit&id=<?php echo $question->id ?>'" class="btn btn-primary m-1" style="float:right"><i class="fa fa-magic"></i>&nbsp; Edit This Question</button>
        </div>
      </div>
      </div>
		<?php }else{ $crs = new course; ?>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <strong class="card-title">Questions</strong>
                  <a href="?questions=trash" style="margin-left: 20px;" type="button" class="btn btn-outline-danger float-right m-1"><i class="fa fa-trash"></i>Trash</a>
									<a type="button" <?php echo (($crs->noCourses() == 1) ? ' onclick="swal.fire(\'Add Topics First!\',\'You must Add a topic before adding any Tests\',\'warning\')" ' : ' href="?questions=import" ') ?> class="btn btn-outline-success float-right m-1"><i class="fa fa-upload"></i>Import/Export</a>
                  <a type="button" <?php echo (($crs->noCourses() == 1) ? ' onclick="swal.fire(\'Add Topics First!\',\'You must Add a topic before adding any Tests\',\'warning\')" ' : ' href="?questions=add" ') ?> class="btn btn-outline-primary float-right m-1"><i class="fa fa-plus"></i>Add New Question</a>
                </div>

                <div class="card-body">
                  <table id="questionsTable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Question</th>
                        <th>Course</th>
                        <th>Type</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
							if(isset($_GET['course']) && !empty($_GET['course'])){
								$allQuestions = $question->getByCourse($_GET['course']);
							}
							else {
								$allQuestions = $question->getAll();
							}
							foreach ($allQuestions as $question) { ?>
                  <tr>
	                  <td style="max-width:300px"><?php echo $question->question ?></td>
	                  <td><?php echo $question->course ?></td>
	                  <td>
											<?php if ($question->type == 0)
												echo '<span class="badge badge-primary">Multiple Choice</span>';
											elseif ($question->type == 1)
												echo '<span class="badge badge-primary">True & False</span>';
											elseif ($question->type == 2)
												echo '<span class="badge badge-primary">Complete</span>';
											elseif ($question->type == 3)
												echo '<span class="badge badge-primary">Multiple Select</span>';
											elseif ($question->type == 4)
												echo '<span class="badge badge-primary">Matching</span>';
											elseif ($question->type == 5)
												echo '<span class="badge badge-primary">Essay</span>';?>
										</td>
										<td><button type="button" onclick="window.location.href='?questions=view&id=<?php echo $question->id ?>'" class="btn btn-outline-success btn-sm"><i class="fa fa-eye"></i> View</button></td>
										<td><button type="button" onclick="window.location.href='?questions=edit&id=<?php echo $question->id ?>'" class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i> Edit</button></td>
	                  <td><button type="button" onclick="window.location.href='app/controller/question.inc.php?deleteQuestion=<?php echo $question->id ?>'" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></td>
              </tr>
				<?php		}?>
                    </tbody>
                  </table>
                </div>
              </div>




            </div>
        <?php } ?>
      </div>
    </div>
  <?php
define('ContainsDatatables', true);

require_once 'footer.php';
?>
