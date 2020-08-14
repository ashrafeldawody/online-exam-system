<?php
if (!defined('NotDirectAccess')) {
    die('Direct Access is not allowed to this page');
}
$obj = new instructor();
$usr = $obj->getByEmail($_SESSION['mydata']->email);
?>
<body>
  <nav class="navbar navbar-icon-top navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <?php
          if($_SESSION['mydata']->isAdmin){ ?>
            <li class="nav-item <?php echo (isset($_GET['instructors'])?'active':'') ?>">
              <a class="nav-link" href="?instructors">
                <i class="fa fa-group"></i>
                Instructors
              </a>
            </li>
            <li class="nav-item <?php echo (isset($_GET['students'])?'active':'') ?>">
              <a class="nav-link" href="?students">
                <i class="fa fa-group"></i>
                Students
              </a>
            </li>
            <li class="nav-item <?php echo (isset($_GET['results'])?'active':'') ?>">
              <a class="nav-link" href="?results">
                <i class="fa fa-group"></i>
                Results
              </a>
            </li>
          <?php }else{ ?>
        <li class="nav-item <?php echo (empty($_GET)?'active':'') ?>">
          <a class="nav-link" href=".">
            <i class="fa fa-home"></i>
              Home
            <span class="sr-only">(current)</span>
            </a>
        </li>
        <li class="nav-item <?php echo (isset($_GET['groups'])?'active':'') ?>">
          <a class="nav-link" href="?groups">
            <i class="fa fa-group"></i>
            Groups
          </a>
        </li>
        <li class="nav-item <?php echo (isset($_GET['courses'])?'active':'') ?>">
          <a class="nav-link" href="?courses">
            <i class="fa fa-file"></i>
            Courses
          </a>
        </li>
        <li class="nav-item <?php echo (isset($_GET['questions'])?'active':'') ?>">
          <a class="nav-link" href="?questions">
            <i class="fa fa-question"></i>
            Questions
          </a>
        </li>
        <li class="nav-item <?php echo (isset($_GET['tests'])?'active':'') ?>">
          <a class="nav-link" href="?tests">
            <i class="fa fa-check-square"></i>
            Tests
          </a>
        </li>

        <li class="nav-item <?php echo (isset($_GET['results'])?'active':'') ?>">
          <a class="nav-link" href="?results">
            <i class="fa fa-bar-chart"></i>
            Results
          </a>
        </li>
        <li class="nav-item <?php echo (isset($_GET['students'])?'active':'') ?>">
          <a class="nav-link" href="?students">
            <i class="fa fa-bar-chart"></i>
            Students
          </a>
        </li>

<?php } ?>
      </ul>
      <span class="badge badge-secondary">Hello <?php echo $_SESSION['mydata']->name ?></span>

      <ul class="navbar-nav ">
        <li class="nav-item">
          <a class="nav-link" href="?profile">
            <i class="fa fa-user"></i>
            Profile
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="?logout">
            <i class="fa fa-sign-out"></i>
            Logout
          </a>
        </li>
      </ul>
    </div>
  </nav>
