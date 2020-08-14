<?php
   if (!defined('NotDirectAccess')) {
       die('Direct Access is not allowed to this page');
   }
   require_once 'header.php';
   require_once 'navbar.php';
  $rep = new report();
  $topTenQuestions = $rep->getTopTenAnswered();
  $hardestQuestions = $rep->hardestTenAnswered();
  $topTenResults = $rep->getTopTenResults();
  $LatestTenResults = $rep->getLatestTenResults();
  $mostActiveTests = $rep->mostActiveTests();

   ?>

    <div class="flex">
      <div class="col-lg-6 statCard">
        <div class="card right">
          <div class="card-header">
            <strong class="card-title">Hardest Questions<small> (Wrongly Answered)</small></strong>
          </div>
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Question</th>
                  <th scope="col">Course</th>
                  <th scope="col">Answered<small> (times)</small></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                 foreach ($hardestQuestions as $question){ ++$i; ?>
                <tr>
                  <th><?php echo $i ?></th>
                  <td><?php echo substr($question->question, 0, 70) ?>... <a href="?questions=view&id=<?php echo $question->id ?>">View Question</a></td>
                  <td><?php echo $question->course ?></td>
                  <td><?php echo $question->correct ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card right">
          <div class="card-header">
            <strong class="card-title">Easiest Questions<small> (Correctly Answered)</small></strong>
          </div>
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Question</th>
                  <th scope="col">Course</th>
                  <th scope="col">Answered<small> (times)</small></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                 foreach ($topTenQuestions as $question){ ++$i; ?>
                <tr>
                  <th><?php echo $i ?></th>
                  <td><?php echo substr($question->question, 0, 70) ?>... <a href="?questions=view&id=<?php echo $question->id ?>">View Question</a></td>
                  <td><?php echo $question->course ?></td>
                  <td><?php echo $question->correct ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
      <div class="statCard">
        <div class="card left">
          <div class="card-header">
            <strong class="card-title">Latest Results</strong>
          </div>
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Test</th>
                  <th scope="col">Student</th>
                  <th scope="col">Time</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                 foreach ($LatestTenResults as $result){ ++$i; ?>
                <tr>
                  <th scope="row"><a href="?results=view&id=<?php echo $result->resultID ?>"><?php echo $i ?></a></th>
                  <td><a href="?tests=view&id=<?php echo $result->testID ?>"><?php echo $result->testName ?></a></td>
                  <td><span class="badge badge-success"><?php echo $result->studentName ?></span></td>
                  <td><?php echo time_elapsed_string(strtotime($result->endTime)) ?></td>

                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card left">
          <div class="card-header">
            <strong class="card-title">Most Active Tests</strong>
          </div>
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Test</th>
                  <th scope="col">Course</th>
                  <th scope="col">Taken<small> (times)</small></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                 foreach ($mostActiveTests as $test){ ++$i; ?>
                <tr>
                  <th scope="row"><?php echo $i ?></th>
                  <td><a class="" href="?tests=view&id=<?php echo $test->id ?>"><?php echo $test->name ?></a></td>
                  <td><?php echo $test->course ?></td>
                  <td><?php echo $test->taken ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card left">
          <div class="card-header">
            <strong class="card-title">Top Results</strong>
          </div>
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Test</th>
                  <th scope="col">Student</th>
                  <th scope="col">Precent<small> (%)</small></th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 0;
                 foreach ($topTenResults as $result){ ++$i; ?>
                <tr>
                  <th scope="row"><?php echo $i ?></th>
                  <td><a class="mb-0" href="?tests=view&id=<?php echo $result->testID ?>"><?php echo $result->testName ?></a></td>
                  <td><span class="badge badge-success"><?php echo $result->studentName ?></span></td>
                  <td><span class="badge badge-info"><?php echo $result->percent ?>%</span></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

  </div>
  <?php
require_once 'footer.php';
?>
