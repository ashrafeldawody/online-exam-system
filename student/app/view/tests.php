<?php
if (!defined('NotDirectAccess')){
	die('Direct Access is not allowed to this page');
}
require_once 'header.php';
$_test = new test();
$myActiveTest = $_test->getActiveTest(); ?>

<body class="bg-light">

<?php
if(isset($_GET['resume'])){
	if (!$myActiveTest){
		header('Location: ?tests'); exit;
	}
	$_SESSION['CurrentTest'] = $myActiveTest;
?>
<input type="hidden" id="cva" value="<?php echo $myActiveTest->prevQuestion; ?>">
<div id="pagination-container" style="display:none">

</div>
<div class="card" style="width: 80%;border-radius: 0;margin: 0 auto;float: none;margin-bottom: 180px;margin-top: 20px;" id="questionsContainer">

</div>
</div>
<div class="container bg-light boxshadow">

<div class="row">
	<div class="col"><a type="button" class="btn btn-outline-primary btn-lg" style="float: left;" id="PreviousQuestion"><i class="fa fa-chevron-left" aria-hidden="true"></i> Previous Question</a>
	</div>
	<div class="col text-center">
		<?php if(!($myActiveTest->remainingTime >= 0)){ ?>
			<button class="btn btn-danger btn-lg" id="timer"><?php echo $myActiveTest->endTime ?></button>
		<?php }else { ?>
		<button class="btn btn-danger btn-lg" id="timer">00:00</button>
		<script type="text/javascript">setTimer(<?php echo ($myActiveTest->remainingTime < 0)? 0 : $myActiveTest->remainingTime ?>);</script>
		<?php } ?>
	</div>
	<div class="col"><a type="button" class="btn btn-outline-primary btn-lg" style="float: right;" id="NextQuestion">Next Question <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
	</div>
</div>
</div>


<?php
define('ContainsPagination', true);

}elseif((!empty($_GET['id']) || !empty($_GET['code'])) && isset($_GET['start'])){
 	require_once 'navbar.php';
	$myTest = (isset($_GET['code']) ? $_test->getTestByCode($_GET['code']) : $_test->getTest($_GET['id']));
	if (empty($myTest)){
	header('Location: ?tests');exit;
	}
	$_SESSION['CurrentTest'] = $myTest;
 ?>
		<div class="container">
			<div class="card" style="margin-top:50px">
				<div class="card-header">
					<strong class="card-title">Test</strong>
				</div>
				<div class="card-body">
					<div class="alert-box alert-default">
						<p>
						<span class="titlename bold">Instructions</span></p>
						<ul class="list-group">
							<li class="list-group-item">
								<div> <i class="fa fa-user"></i> Your Name <span class="font-weight-bold pull-right"><?php echo $_SESSION['student']->name ?></span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-address-book"></i> Your ID <span class="badge badge-secondary pull-right"><?php echo $_SESSION['student']->id ?></span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-clock-o"></i> Test Starts <span class="badge badge-success pull-right"><?php echo date("Y-m-d h:i a", strtotime($myTest->startTime)); ?></span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-clock-o"></i> Test Ends <span class="badge badge-success pull-right"><?php echo date("Y-m-d h:i a", strtotime($myTest->endTime)); ?></span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-th-list"></i> No. Of Questions <span class="badge badge-primary pull-right"><?php echo $myTest->questions ?></span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-clock-o"></i> Test Duration <span class="badge badge-danger pull-right"><?php echo $myTest->duration ?> Minutes</span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-percent"></i> Pass Precent: <span class="badge badge-warning pull-right"><?php echo $myTest->passPercent ?>%</span></div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-tasks"></i> Test won't be counted until you submit it.</div>
							</li>
							<li class="list-group-item">
								<div> <i class="fa fa-check-square-o"></i>MCQ Questions can have multiple Answers</div>
							</li>

						</ul>
						<button type="button" id="StartTest" data-id="<?php echo (isset($_GET['id'])? $_GET['id']:0) ?>" data-code="<?php echo (isset($_GET['code'])? $_GET['code']:0) ?>" data-iscode="<?php echo (isset($_GET['code'])? 1:0) ?>" class="btn btn-primary btn-lg btn-block">Start Test</button>

					</div>
				</div>
			</div>
		</div>
		<?php
}else{
	require_once 'navbar.php';
	$myTests = $_test->getMyTests(); ?>
		    <div class="container mt-3">
					<?php
						if (isset($_SESSION['test'])){
							$link = $_SESSION['linkID'];
							unset($_SESSION['linkID']);
							header('Location: ?tests&code='. $link .'&start');
							}
							$checkActiveTest = $_test->checkActiveTest();
							if ($checkActiveTest != 0){
								echo '<div class="alert alert-primary" role="alert">You have an active test! <a href="?tests&resume">Resume Test</a></div>';
							}
						 ?>
		      <div class="card">
		        <div class="card-header">
		          <strong class="card-title">Tests From Groups
		          </strong>

		        </div>
		        <div class="card-body table-responsive">
		            <table class="table">
		              <thead>
		                <tr>
		                  <th>Test</th>
											<th>Group</th>
		                  <th>Instructor</th>
		                  <th>End date</th>
		                  <th>Status</th>
		                  <th>Start</th>
		                </tr>
		              </thead>
		              <tbody>
									<?php foreach($myTests as $myTest){ ?>
		                <tr>
		                  <th><?php echo $myTest->name ?></th>
											<td><?php echo $myTest->groupName ?></td>
		                  <td><?php echo $myTest->instructor ?></td>
		                  <td><?php echo (!empty($myTest->endTime)) ? date('m-d-Y h:i A', strtotime($myTest->endTime)) : 'Not Determined';?></td>
											<td><?php echo $myTest->status ?></td>
		                  <td>
		                    <a type="button" href="?tests&id=<?php echo $myTest->id ?>&start" class="btn btn-outline-primary mb-1"<i class="fa fa-plus"></i>Start Test</a>
		                </td>
		                </tr>
									<?php } ?>
		              </tbody>
		            </table>
		</div>
		</div>
		</div>


<?php
}

require_once 'footer.php';
?>
