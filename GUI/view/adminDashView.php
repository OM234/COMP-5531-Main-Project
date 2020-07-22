<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>zz</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <script src="js/dashboard.js"></script>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Administrator Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAllUsers"; ?>>View All Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewJobs"; ?>>View All Jobs</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAccountSettings"; ?>>Account Settings</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="row justify-content-center m-1 d-none" id ="accSettingsNavbar">
        <div class='col-8'>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">Account Settings</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'] . "?tab=viewContactInfo"; ?>">Contact
                                Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewPasswordChange"; ?>>Password</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div id="viewJobs"> </div>
    <div id="viewUsers"> </div>
    <div id="accountSettings" class="container"></div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>