<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
	    <a class="navbar-brand"><h5>Online Exam System</h5></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar10">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar10">
            <ul class="navbar-nav nav-fill w-100" style="color:white">
                <li class="nav-item <?php echo (isset($_GET['tests'])?'active':'') ?>">
                  <a class="nav-link" href="?tests"><h6><i class="fa fa-align-left" aria-hidden="true"></i> Tests</h6></a>
                </li>
                <li class="nav-item <?php echo (isset($_GET['groups'])?'active':'') ?>">
                  <a class="nav-link " href="?groups"><h6><i class="fa fa-users" aria-hidden="true"></i> Groups</h6></a>
                </li>
                <li class="nav-item <?php echo (isset($_GET['results'])?'active':'') ?>">
                    <a class="nav-link" href="?results"><h6><i class="fa fa-line-chart" aria-hidden="true"></i> Results</h6></a>
                </li>
                <li class="nav-item <?php echo (isset($_GET['profile'])?'active':'') ?>">
                    <a class="nav-link" href="?profile"><h6><i class="fa fa-user" aria-hidden="true"></i> Profile</h6></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?logout"><h6><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</h6></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
