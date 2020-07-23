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
        <a class="navbar-brand" href="#">Employer Dashboard</a>
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
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=postJob"; ?>>Post Jobs</a>
                </li>
                <li class="nav-item">
                    <!-- <a class="nav-link" href="#?tab=viewJobs" onclick="viewJobsEmployer()">View Posted Jobs</a>-->
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewJobs"; ?>>View Posted Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewApplications"; ?>>View
                        Applicants</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAccountSettings"; ?>>Account
                        Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=signout"; ?>> Sign out</a>
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
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewPaymentInfo"; ?>>Payment
                                Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAccBalance"; ?>>Account
                                Balance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewPasswordChange"; ?>>Password</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div id="viewJobs"></div>
    <div id="viewApplications" class="container"></div>
    <div id="accountSettings" class="container"></div>
    <div id="postJob" class="row justify-content-center postJob">
        <div class="col-8">
            <form name="postJobForm"
                  method="post"
                  onsubmit="return validateForm()"
                  action=<?php echo $_SERVER['PHP_SELF'] . "?tab=postJob"; ?>>
                <div class="form-group">
                    <label for="title"><b>Job Title</b></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Software Engineer"
                           required>
                    <span id="titleError"></span>
                </div>
                <div class="form-group">
                    <label for="category"><b>Category</b></label>
                    <select id="category" class="form-control" name="category" required>
                        <option selected>Technology</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description"><b>Description (Max 50 characters)</b></label>
                    <textarea class="form-control" type="text" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="numOpenings"><b>Number of openings</b></label>
                    <input type="text" class="form-control numInput" id="numOpenings" placeholder="1..."
                           name="numOpenings" required>
                    <span id="error" style="color: red"></span>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit">
            </form>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
