<?php
if (!defined('NotDirectAccess')) {
    die('Direct Access is not allowed to this page');
}
require_once 'header.php';
require_once 'header.php';
require_once 'navbar.php';
$_assign = new assign;
    if (($_GET['tests'] == "addQuestions") && (isset($_GET['id']))) { ?>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <strong class="card-title">Questions</strong>
            <a href="?questions=add" type="button" class="btn btn-outline-primary float-right mb-1"><i class="fa fa-plus"></i>Add New Question</a>
            <a href="?tests=view&id=<?php echo $_GET['id']?>" type="button" class="btn btn-outline-primary float-right mb-1" style="margin-right: 22px;"><i class="fa fa-arrow-circle-left"></i>  Back To Test</a>
          </div>
          <div class="card-body">
            <div class="row form-group">
              <div class="col col-md-3">
                <label>You selected</label>
              </div>
              <div class="col-12 col-md-9">
                <span class="badge badge-success" id="counter">0</span> Questions
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="total">Total Points</label>
              </div>
              <div class="col-12 col-md-9">
                <span class="badge badge-success" id="total">0</span> Point
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id="AssignQuestionsTable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th></th>
                  <th class="d-none">ID</th>
                  <th>Question</th>
                  <th>Course</th>
                  <th>Points</th>
                  <th>Type</th>
                  <th>Difficulty</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $_tests = new test();
                  $Questions = $_tests->getQuestionsNotInTest($_GET['id']);
                  $qTypes = [0=>'Multiple Choise',1=>'True/False',2=>'Complete',3=>'Multiple Select',4=>'Matching',5=>'Essay'];
                  foreach ($Questions as $question) {
                      echo '<tr>
                            <td></td>
                            <td class="d-none">'. $question->id .'</td>
                            <td style="max-width:300px">'. $question->question .'</td>
                            <td>'. $question->course .'</td>
                            <td class="qDegree">
                              <input class="form-control" value="'. $question->points .'" disabled>
                            </td>
                            <td>'. $qTypes[$question->type] .'</td>
                            <td>'. (($question->difficulty == 1)?'<span class="badge badge-success">Easy</span>':(($question->difficulty == 2)?'<span class="badge badge-warning">Moderate</span>':'<span class="badge badge-danger">Hard</span>')) .'</td>
                      </tr>';
                  }?>
              </tbody>
            </table>
            <form id="testQuestions" action="app/controller/test.inc.php?addQuestionsToTest=<?php echo $_GET['id']; ?>" method="post">

            </form>
            <button type="submit" form="testQuestions" class="btn btn-outline-primary btn-lg btn-block">Add To Test</button>
          </div>
        </div>
      </div>

  <?php }elseif (($_GET['tests'] == "model") && (isset($_GET['id']))) {
    $res = new result;
    $tes = new test;
    $rep = new report;
    $questions = $rep->getQuestionsInTest($_GET['id']);
    $types = [0 =>'Multiple Choice',1 =>'True/False',2 =>'Complete',3 =>'Multiple Select',4 =>'Matching',5 =>'Essay'];
    ?>
    <div class="card">
      <div class="card-header">
        <strong class="card-title"><?php echo $tes->getTestName($_GET['id']); ?><small> Model Answers</small></strong>
        <button type="button" class="btn btn-outline-primary float-right mb-1" onclick="window.print();return false;">Print Model Answer</button>

      </div>
      <div class="card-body">
        <?php
        $types = [0 =>'Multiple Choice',1 =>'True/False',2 =>'Complete',3 =>'Multiple Select',4 =>'Matching',5 =>'Essay'];
        $I = 0;
        foreach($questions as $question){
          $I++; ?>
          <div class="container">
            <div class="card m-4">
              <div class="card-body">
                <?php
                if($question->points == 0)
                echo '<span class="float-right ml-3 badge badge-danger">0 Points</span>';
                elseif($question->points > 0)
                echo '<span class="float-right ml-3 badge badge-success">' .$question->points. ' Points</span>';
                else
                echo '<span class="float-right ml-3 badge badge-warning">Not Yet Reviewed</span>';
                 ?>
                <span class="float-right ml-3 badge badge-secondary">
                  <?php echo $types[$question->type] ?>
                </span>
                <blockquote class="blockquote">
                  <?php echo '<span class="badge badge-secondary">' .$I. ') </span>' .$question->question ?>
                </blockquote>
                <hr>
                <?php $correctAnswers = $res->getCorrectAnswers($question->id);?>
                <?php if($question->type == 4){ ?>
                  <div class="row mt-4">
                    <div class="col-12 text-center" style="font-size:1.2rem">
                      <h5 class="ml-3 text-left">Correct Answers</h5>
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
                  <?php }elseif($question->type == 0 || $question->type == 3){ ?>
                    <div class="mt-5 ml-5 mr-5">
                          <div class="mt-4">
                            <div class="row">
                              <?php foreach ($correctAnswers as $ans)
                                echo '<div class="col-5 p-3 correctAnswer mr-5">'. $ans->answer .'</div>'; ?>
                            </div>
                        </div>
                    </div>
                    <?php }elseif($question->type == 1){ ?>
                      <div class="row">
                        <div class="col-6">
                          Correct Answer: <span class="badge badge-success"><?php echo (($question->isTrue)?'True':'False'); ?></span>
                        </div>
                      </div>
                      <?php }elseif($question->type == 2){ ?>
                        <div class="row">
                          <div class="col-6">
                            Accepted Answer:
                            <?php foreach($correctAnswers as $a) echo '<span class="badge badge-primary ml-3">' . $a->answer.'</span>'; ?>
                          </div>
                        </div>
                        <?php } ?>

              </div>
            </div>
          </div>
          <?php } ?>
      </div>
    </div>
  <?php }elseif ($_GET['tests'] == "sessions") { ?>
    <div class="card">
      <div class="card-header">
        <strong class="card-title">Tests Sessions <small>(taken at least one time)</small></strong>
      </div>
      <div class="card-body">
        <table id="bootstrap-data-table" class="table table-bordered">
          <thead>
            <tr>
              <th>Test</th>
              <th>Assign Type</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Taken</th>
              <th>Result Released</th>
              <th>View Answers</th>
            </tr>
          </thead>
          <tbody>
            <?php $_test = new test();
              $allSessions = $_test->getTestSessions();
              foreach ($allSessions as $session) { ?>
              <tr>
                <td><?php echo $session->testName ?></td>
                <td><?php echo $session->type ?></td>
                <td><?php echo date("d-m-Y h:i:s A", strtotime($session->startTime)) ?></td>
                <td><?php echo date("d-m-Y h:i:s A", strtotime($session->endTime)) ?></td>
                <td><span class="badge badge-primary"><?php echo $session->results ?></span></td>
                <td><?php echo ($session->releaseResult == 1)?'<span class="badge badge-success">Yes</span>':'<span class="badge badge-danger">No</span><a href="app/controller/test.inc.php?settingID='.$session->settingID.'&testID='.$session->testID.'" class="text-decoration-none">Release It!</a>' ?></td>
                <td><?php echo ($session->viewAnswers == 0)?'<span class="badge badge-success">Yes</span><a href="app/controller/test.inc.php?hideAnswers='. $session->settingID. '" class="text-decoration-none">Hide it!</a>':'<span class="badge badge-danger">No</span><a href="app/controller/test.inc.php?viewAnswers='. $session->settingID. '" class="text-decoration-none">View it</a>' ?>

                </td>
              </tr>
              <?php } ?>
          </tbody>
      </div>
    </div>

  <?php }elseif (($_GET['tests'] == "view") && (isset($_GET['id']))) {
    $_tests = new test();
    $test = $_tests->getByID($_GET['id']);
    $testRandoms = $_tests->getRandomRules($_GET['id']);
    $readOnly = $_tests->isReadOnly($_GET['id']);
    $testAverage = $_tests->testAverage($_GET['id']);
     ?>
      <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <strong class="card-title"><?php echo $test->name ?></strong>
              <?php if($readOnly){ ?>
              <span class="badge badge-success m-1">Average Grade: <?php echo $testAverage ?>%</span>
              <a href="?tests=model&id=<?php echo $test->id ?>" class="btn btn-outline-success float-right"><i class="fa fa-eye"></i>View Model Answer</a>
            <?php }else{ ?>
              <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#editTest" data-tname="<?php echo $test->name ?>" data-tid="<?php echo $test->id ?>" data-tcourse="<?php echo $test->courseID ?>"><i class="fa fa-edit"></i>Edit</button>
            <?php } ?>
            </div>
            <?php echo ($readOnly? '<span class="alert alert-warning m-4" role="alert">The test is readonly because it was taken by students.  <a href="app/controller/test.inc.php?duplicateTest=' . $test->id .'" class="alert-link">Duplicate Test?</a></span>':''); ?>
            <div class="card-body">
              <input type="hidden" id="testID" value="<?php echo $test->id ?>">
              <div class="row">
              <div class="col-6">
                <label class="control-label mb-3">Test Name:  </label>
                <label class="control-label mb-3"><?php echo $test->name ?></label>
              </div>
              <div class="col-6">
                <label class="control-label mb-3">Course:  </label>
                <label class="control-label mb-3"><strong><?php echo $test->course ?></strong></label>
              </div>
              <div class="col-6">
                <label class="control-label mb-3">Assigned :  </label>
                <label class="control-label mb-3"><span class="badge badge-primary"><?php echo $test->links ?> Link</span></label>
              </div>
              <div class="col-6">
                <label class="control-label mb-3">Results:  </label>
                <label class="control-label mb-3"><span class="badge badge-info"><?php echo $test->inResults ?></span></label>
              </div>
              <div class="col-6">
                <label class="control-label mb-3">Questions:  </label>
                <label class="control-label mb-3"><span class="badge badge-primary"><?php echo (!empty($test->fixedQuestions)? $test->fixedQuestions : '0') ?> Fixed + <?php echo (!empty($test->randomQuestions)? $test->randomQuestions : '0') ?> Random</span></label>
              </div>
              <div class="col-6">
                <label class="control-label mb-3">Total Points:  </label>
                <label class="control-label mb-3"><span class="badge badge-info"><?php echo $test->TestGrade ?> + Random Points</span></label>
              </div>
              </div>
              <br>
              <br>
              <br>
              <hr>
              <?php if(!$readOnly){ ?>
              <div class="container">
                  <div class="form-row align-items-center">
                    <div class="col-3">
                      Random Questions:
                    </div>
                    <div class="form-row align-items-center">
                    <div class="col-auto">
                      Add
                    </div>
                    <div class="col-auto">
                       <input type="number" class="form-control" style="max-width:80px" id="numofQ" placeholder="No. Of Questions" value="1" min="1" required>
                    </div>
                    <div class="col-auto">
                      <select name="difficulty" id="difficulty" style="max-width:100px" class="form-control" required>
													<option value="1">Easy</option>
													<option value="2">Moderate</option>
													<option value="3">Hard</option>
										</select>
                    </div>
                    <div class="col-auto">
                        Questions From
                    </div>
                    <div class="col-auto">
                      <select name="Course" id="select" class="form-control"  required oninvalid="this.setCustomValidity('Please Add New Topic Before Adding Tests')">
                        <?php
												$cat = new course;
												$parents = $cat->getAllParents();
												foreach ($parents as $parent) {
													echo '<optgroup label="'. $parent->name .'">';
													$childs = $cat->getAllChilds($parent->id);
													foreach ($childs as $child) {
																echo '<option value="'. $child->id .'">'. $child->name .'</option>';
													}
													echo '</optgroup>';
												}
												?>
                      </select>
                    </div>
                    <div class="col-auto">
                      <button type="button" id="createRandomRule" class="btn btn-primary mb-1">Add</button>
                    </div>
                    </div>

                  </div>

                  <div class="form-row align-items-center">

                    <div class="col-3">
                    </div>
                    <input type="hidden" id="tstID" value="<?php echo $_GET['id']; ?>">
                    <ul class="list-group" id="RandomRules">
                      <?php
                      foreach($testRandoms as $rand){ ?>
                        <li class="list-group-item randomli">
                          <div class="row">
                            <?php echo ($rand->validCount?'<i class="fa fa-exclamation-circle text-danger circle-warning" title="No enought Questions" aria-hidden="true"></i>':'') ?>
                            <div class="col-auto">
                              Adding
                            </div>
                            <div class="col-auto">
                              <span class="badge badge-primary"><?php echo $rand->questionsCount ?></span>
                            </div>
                            <div class="col-auto">
                              <?php $difficultLevels = [1=>'Easy',2=>'Moderate',3=>'Hard'] ?>
                              <?php $difficultColors = [1=>'badge-success',2=>'badge-warning',3=>'badge-danger'] ?>
                              <span class="badge <?php echo $difficultColors[$rand->difficulty]; ?> diffspan"><?php echo $difficultLevels[$rand->difficulty];?></span>
                            </div>
                            <div class="col-auto">
                              Questions From
                            </div>
                            <div class="col-auto">
                              <span class="badge badge-info crsname"><?php echo $rand->course ?></span>
                            </div>
                              <i class="fa fa-trash-o deleteCrsQuestions text-danger circle-warning" data-diff = "<?php echo $rand->difficulty; ?>" data-tid="<?php echo $rand->testID ?>" data-cid="<?php echo $rand->courseID ?>"></i>
                            </div>
                          </li>
                        <?php } ?>
                    </ul>
                    <div class="col-sm">

                    </div>

                  </div>
              </div>
            <?php } ?>
              <hr>
              <br>
              <h4>Test Assigns</h4>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Status</th>
                      <th>-</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php
                    $invitations = $_assign->getTestInvitations($test->id);
                    foreach($invitations as $v){ ?>
                      <tr>
                        <td><?php echo (empty($v->name)? 'No Name':$v->name) ?></td>
                        <td><?php if ($v->status == 1)  echo '<i class="fa fa-calendar" style="color:#28a745" title="Available" aria-hidden="true"></i> Active';
                          elseif ($v->status == 2) echo '<i class="fa fa-calendar" style="color:gray" title="Not Available" aria-hidden="true"></i> Not Started Yet';
                          else echo '<i class="fa fa-calendar" style="color:gray" title="Not Available" aria-hidden="true"></i> Not Active'; ?>
                        </td>
                        <td><a class="showLink"
                          data-random="<?php echo ($v->random?'Yes':'No') ?>"
                          data-pass="<?php echo $v->passPercent ?>%"
                          data-duration="<?php echo $v->duration ?> Minutes"
                          data-sendtostudent="<?php echo ($v->sendToStudent?'Yes':'No') ?>"
                          data-startTime="<?php echo $v->startTime ?>"
                          data-endTime="<?php echo $v->endTime ?>"
                          data-id="<?php echo $v->id ?>"
                          data-link="<?php echo "http://" . $_SERVER['SERVER_NAME'] . '/student/?tests&code=' . $v->invite . '&start' ?>">View</a> |
                            <a href="?assign&AssignToLink&linkID=<?php echo $v->id ?>">Settings</a> |
                            <a href="?results&code=<?php echo $v->id ?>">Results</a> |
                            <a href="app/controller/assign.inc.php?deleteLink=<?php echo $v->id ?>" onclick="return confirm('Are you sure You want to delete this Link?')">Delete</a>
                        </td>
                      </tr>

                  <?php } ?>
                </tbody>
              </table>


                  <hr>
                  <a href="?assign&test=<?php echo $test->id ?>" id="assignButton" data-questions="<?php echo $test->fixedQuestions + $test->randomQuestions ?>" type="button" class="btn btn-outline-success float-right mb-1"><i class="fa fa-plus"></i>Assign</a>

            </div>
            <br>
            <?php if(!$readOnly){ ?>
            <div class="card-header" style="background:inherit;">
            <h4 class="float-left">Test Questions</h4>
            <a href="?tests=addQuestions&id=<?php echo $test->id ?>" type="button" class="btn btn-outline-primary float-right m-1"><i class="fa fa-plus"></i> Add Fixed Questions</a>
            <form id="testQuestions" action="app/controller/test.inc.php?deleteQuestionsFromTest=<?php echo $test->id ?>" method="post">
              <button class="btn btn-outline-danger float-right m-1" type="submit"><i class="fa fa-trash"></i>Delete Selected</button>
            </form>
            </div>
            <div class="card-body">
              <table id="deleteQuestionsFromTest" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th></th>
                    <th class="d-none">ID</th>
                    <th>Question</th>
                    <th>Course</th>
                    <th>Points</th>
                    <th>Type</th>
                    <th>Difficulty</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $_tests = new test();
                  $Questions = $_tests->getQuestionsInTest($_GET['id']);
                  $qTypes = [0=>'Multiple Choise',1=>'True/False',2=>'Complete',3=>'Multiple Select',4=>'Matching',5=>'Essay'];
                  foreach ($Questions as $question) { ?>
                    <tr>
                      <td></td>
                      <td class="d-none">
                        <?php echo  $question->id ?>
                      </td>
                      <td class="text-wrap" style="max-width:300px">
                        <?php echo  $question->question ?>
                      </td>
                      <td>
                        <?php echo  $question->course ?>
                      </td>
                      <td class="qDegree text-center" name="qDegree">
                        <span class="badge badge-success"><?php echo $question->questionGrade ?></span>
                      </td>
                      <td><?php echo $qTypes[$question->type]; ?></td>
                      <td><?php echo (($question->difficulty == 1)?'<span class="badge badge-success">Easy</span>':(($question->difficulty == 2)?'<span class="badge badge-warning">Moderate</span>':'<span class="badge badge-danger">Hard</span>')) ?></td>
                    </tr>
                    <?php  } ?>
                </tbody>
              </table>

            </div>
          <?php }else{ ?>
            <h3 class="ml-3">Questions</h3>
            <?php
              $rep = new report;
              $questions = $rep->getQuestionsInTest($_GET['id']);
              $i = 0;
              foreach($questions as $question){
                $i++;
                $questionDetails = $rep->getQuestionReport($question->id);
                $questionAnswers = $rep->questionAnswersStats($question->id,$question->type);
                $crt = $questionDetails->rightAnswers;
                $wrg = $questionDetails->wrongAnswers;
                $ttl = $questionDetails->inResults;
                $correct = round(($crt / $ttl) * 100,0);
                $wrong = round(($wrg / $ttl) * 100,0);
            ?>
            <div class="card m-1">
              <div class="card-body">
                <blockquote class="blockquote">
                  <?php echo $i ?>) <?php echo $questionDetails->question ?>
                  <span class="badge badge-<?php echo ($correct<50)?'danger':'success' ?>">
                    <?php echo $correct ?>%
                  </span>
                  <?php echo (($questionDetails->difficulty == 1)?'<span class="badge badge-success">Easy</span>':(($questionDetails->difficulty == 2)?'<span class="badge badge-warning">Moderate</span>':'<span class="badge badge-danger">Hard</span>')) ?>
                </blockquote>
                <?php if ($questionDetails->type < 4){ ?>
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#question<?php echo $i ?>" aria-expanded="true" aria-controls="collapseOne">
                  View Stats
                </button>
                <a class="btn btn-link float-right" type="button" href="?questions=view&id=<?php echo $questionDetails->id; ?>">
                  View Question
                </a>
                <div id="question<?php echo $i ?>" class="collapse">
                  <div class="card-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Answer</th>
                          <th scope="col">Selected</th>
                          <th scope="col">Correct</th>
                        </tr>
                      </thead>
                      <tbody>
                    <?php
                    foreach($questionAnswers as $answer){ ?>
                      <tr>
                        <td><?php echo $answer->answer ?></td>
                        <td><?php echo $answer->c ?></td>
                        <td><?php echo ($answer->isCorrect == 0)? 'No':'Yes' ?></td>
                      </tr>
                      <?php
                    }
                     ?>
                   </tbody>
                 </table>

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
              </div>
                </div>
              <?php } ?>

              </div>
            </div>

          <?php               } } ?>

          </div>
        </div>
        <div class="modal fade" id="editTest" tabindex="-1" role="dialog" aria-labelledby="editTestLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editTestLabel">Update Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="app/controller/test.inc.php?editTest" id="edittestform" method="post" class="">
                  <input type="hidden" name="testid">
                  <div class="row form-group">
                    <div class="col col-md-3">
                      <label for="testName" class=" form-control-label">Test Name:</label>
                    </div>
                    <div class="col-12 col-md-9">
                      <input type="text" name="testName" rows="1" placeholder="Test Name..." class="form-control" required>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col col-md-3">
                      <label for="select" class=" form-control-label">Course</label>
                    </div>
                    <div class="col-12 col-md-9">
                      <select name="Course" class="form-control"  required oninvalid="this.setCustomValidity('Please Add New Topic Before Adding Tests')">
                        <?php
                          $cat = new course();
                          $parents = $cat->getAllParents();
                          foreach ($parents as $parent) {
                                  echo '<option value="'. $parent->id .'">'. $parent->name .'</option>';                          } ?>
                      </select>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="submit" form="edittestform" class="btn btn-primary">
                  <i class="fa fa-dot-pen"></i> Update
                </button>
              </div>

            </div>

          </div>
        </div>
    <?php } elseif ($_GET['tests'] == "trash") { ?>
          <div class="col-md-12">
            <?php
                                    if (isset($_SESSION['error'])) {
                                        foreach ($_SESSION['error'] as $err) {
                                            echo '<div class="sufee-alert alert alert-danger alert-dismissible fade show">
									<span class="badge badge-pill badge-danger">Failed</span>'. $err . '</div>';
                                        }
                                    }
                                    unset($_SESSION['error']);
                                    if (isset($_SESSION['info'])) {
                                        foreach ($_SESSION['info'] as $info) {
                                            echo '<div class="sufee-alert alert alert-success alert-dismissible fade show">
										<span class="badge badge-pill badge-success">Success</span>'. $info . '</div>';
                                        }
                                    }
                                        unset($_SESSION['info']);
                                        ?>
              <div class="card">
                <div class="card-header">
                  <strong class="card-title">Tests Trash</strong>
                  <a href="?tests" type="button" class="btn btn-outline-primary float-right mb-1"><i class="fa fa-arrow-circle-left"></i>  Back To Tests</a>
                </div>

                <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Test</th>
                        <th>Course</th>
                        <th>Questions</th>
                        <th>Results</th>
                        <th>-</th>
                        <th>-</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $_test = new test();
                      $allTests = $_test->getDeleted();
                      foreach ($allTests as $test) { ?>
                        <tr>
                          <td><?php echo $test->name ?></td>
                          <td><?php echo $test->course ?></td>
                          <td><span class="badge badge-success"><?php echo $test->fixedQuestions + $test->randomQuestions ?></span></td>
                          <td><span class="badge badge-success"><?php echo $test->inResults ?></span></td>
                          <td>
                            <button type="button" onclick="window.location.href='app/controller/test.inc.php?restoreTest=<?php echo $test->id ?>'" class="btn btn-outline-primary btn-block"><i class="fa fa-undo"></i> Restore</button>
                          </td>
                          <td>
                            <button type="button" class="btn btn-outline-danger btn-block" <?php echo ($test->inResults == 0)? '':'disabled' ?> onclick="javascript: if (confirm('Are you sure you want to delete this Test?')) { window.location.href='app/controller/test.inc.php?delete=<?php echo $test->id ?>'}"><i class="fa fa-trash"></i>Permanently Delete</button>
                          </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
      <?php
} else { ?>
        <div class="content mt-3">
          <div class="animated fadeIn">
              <div class="col-md-12">
                <?php
                if (isset($_SESSION['error'])) {
                    foreach ($_SESSION['error'] as $err) {
                        echo '<div class="sufee-alert alert alert-danger alert-dismissible fade show">
												<span class="badge badge-pill badge-danger">Failed</span>'. $err . '</div>';
                      }
                  }
                unset($_SESSION['error']);
                if (isset($_SESSION['info'])) {
                    foreach ($_SESSION['info'] as $info) {
                        echo '<div class="sufee-alert alert alert-success alert-dismissible fade show">
													<span class="badge badge-pill badge-success">Success</span>'. $info . '</div>';
                      }
                  }
                unset($_SESSION['info']);
                $crs = new course();
                 ?>
                  <div class="card">
                    <div class="card-header">
                      <strong class="card-title">Tests</strong>
                      <a href="?tests=trash" type="button" class="btn btn-outline-danger float-right m-1"><i class="fa fa-trash"></i> Trash</a>
                      <a href="?tests=sessions"type="button" class="btn btn-outline-primary float-right m-1"><i class="fa fa-calendar"></i> Test Sessions</a>
                      <button type="button" <?php echo (($crs->noCourses() == 1) ? ' onclick="swal.fire(\'Add Topics First!\',\'You must Add a topic before adding any Tests\',\'warning\')" ' : ' data-toggle="modal" data-target="#addnewtest" ') ?> class="btn btn-outline-success float-right m-1"><i class="fa fa-plus"></i> Add New Test</button>
                    </div>

                    <div class="card-body">
                      <table id="testsTable" class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Test</th>
                            <th>Course</th>
                            <th>Questions</th>
                            <th>Results</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $_test = new test();
                            $allTests = $_test->getAll();
                            foreach ($allTests as $test) { ?>
                            <tr>
                              <td>
                                <?php echo $test->name ?>
                              </td>
                              <td>
                                <?php echo $test->course ?>
                              </td>
                              <td><span class="badge badge-success"><?php echo $test->fixedQuestions + $test->randomQuestions ?></span></td>
                              <td><span class="badge badge-success"><?php echo $test->inResults ?></span></td>
                              <td>
                                <button type="button" class="btn btn-outline-success btn-block" data-toggle="modal" onclick="javascript: window.location.href='?tests=view&id=<?php echo $test->id ?>'"><i class="fa fa-eye"></i>View</button>
                              </td>
                              <td>
                                <button type="button" class="btn btn-outline-primary btn-block" data-toggle="modal" onclick="javascript: window.location.href='?results&testID=<?php echo $test->id ?>'"><i class="fa fa-bar-chart"></i>Results</button>
                              </td>
                              <td>
                                <button type="button" class="btn btn-outline-danger btn-block" onclick="javascript: if (confirm('Are you sure you want to delete this Test?')) { window.location.href='app/controller/test.inc.php?deleteTest=<?php echo $test->id ?>'}"><i class="fa fa-trash"></i>Delete</button>
                              </td>
                            </tr>
                            <?php if($test->links > 0){ ?>
                              <?php } } ?>
                        </tbody>
                    </div>
                  </div>




              </div>


          </div>
          <div class="modal fade" id="addnewtest" tabindex="-1" role="dialog" aria-labelledby="addnewtestLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addnewtestLabel">Add New Test</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                      <form action="app/controller/test.inc.php?addTest" id="newtest" method="post" class="">
                        <div class="row form-group">
                          <div class="col col-md-3">
                            <label for="testName" class=" form-control-label">Test Name:</label>
                          </div>
                          <div class="col-12 col-md-9">
                            <input type="text" name="testName" rows="1" placeholder="Test Name..." class="form-control" required>
                          </div>
                        </div>
                        <div class="row form-group">
                          <div class="col col-md-3">
                            <label for="select" class=" form-control-label">Course</label>
                          </div>
                          <div class="col-12 col-md-9">
                            <select name="Course" id="select" class="form-control" required oninvalid="this.setCustomValidity('Please Add New Topic Before Adding Tests')">
      												<?php
      													$cat = new course;
      													$parents = $cat->getAllParents();
      													foreach ($parents as $parent) {
      														echo '<option value="'. $parent->id .'">'. $parent->name .'</option>';
      													}
      													?>
      												</select>
                          </div>
                        </div>
                      </form>
                </div>
                <div class="modal-footer text-center">
                  <button type="submit" form="newtest" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                  </button>
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
