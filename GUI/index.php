<?php

/**
 * index.php
 *
 * Login page, authenticate username and password,
 * if login success, go to dashboard, start a session for this user.
 * Set the session attribute, "username", "password", "accountType".
 *
 * If user already login, redirect to dashboard page.
 */

session_start();
//session_destroy();

/* if request method is get, check login status first,
if already login, redirect to dashborad */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == true) {
        echo "Already logged in";
    } else {
        echo "Not logged in";
    }
}



/* if request method is post, compare user's input username and password with
   data stored in database */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    echo "username: " . $username . "<br>";
    $password = $_POST['password'];
    echo "password: " . $password . "<br>";
    $accountType = $_POST['radioSelect'];
    echo "accountType: " .$accountType . "<br>";

    // if authenticate success, start session, and then set session attributes.
    if (authenticate($username, $password)) {
        session_start();
        $_SESSION['isLogin'] = true;
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        $_SESSION["accountType"] = $accountType;
        goToDashboard($accountType);
    } else {
        echo "<script>alert('Login error, please try again!, use password 1 to login')</script>";
    }
}


// TODO: query in database, selectPasswordByUsername();
function authenticate($username, $password) {
    if ($password == md5("1")) {
        return true;
    }
    return false;
}

// go to page depend on the accountType;
function goToDashboard($accountType) {
    switch($accountType) {
        case 'employer':
            echo "<script>window.location.href = 'employerDash.php';</script>";
            break;
        case 'jobSeeker':
            echo "<script>window.location.href = 'seekerDash.php';</script>";
            break;
        case 'administrator':
            echo "<script>window.location.href = 'adminDash.php';</script>";
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="en" style="font-family: 'Titillium Web', sans-serif;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="COMP 5531 Team" content="">
    <title>COMP 5531: Main Project</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web&display=swap">
    <script src="js/MD5.js"></script>
    <script src="js/scripts.js"></script>
</head>
<body>
<div class="topBanner">
    <p class="toph1"><b>Concordia: Files and Databases: </b> COMP 5531 <span class="toph2"> <b>Main Project:</b> Online Job Portal </span>
    </p>
    <div style="text-align:center">
        <h2>Created by: <span style="font-weight:normal"> Md Tanveer Alamgir, Craig Boucher</span></h2>
        <h2><span style="font-weight:normal"> Osman Momoh and Fan Zou</span></h2>
    </div>
</div>
<div class="briefcase">
    <img class="briefcaseImage" src="Briefcase.png"/>
    <form id="loginForm"
          onSubmit="return login()"
          method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="text" class="username" placeholder="username" name="username" required/>
        <input type="password" id="input-password" class="password" placeholder="password" required/>
        <input type="hidden" id="md5-password" name="password"/>
<!--        <button onclick="login()" class="login">login</button>-->
        <input type="submit" class="login" value="login">
        <div class="radio-toolbar">
            <input type="radio" id="employer" name="radioSelect" value="employer">
            <label for="employer">Employer</label>
            <input type="radio" id="jobSeeker" name="radioSelect" value="jobSeeker">
            <label for="jobSeeker">Job Seeker</label>
            <input type="radio" id="administrator" name="radioSelect" value="administrator">
            <label for="administrator" style="position: relative;">Administrator</label>
        </div>
    </form>
    <a onclick="passPrompt()" class="forgotPass" href="">Forgot password?</a>
    <div class="newAccount">Need an account?
        <a target="_self" href="signUp.html">New account</a>
    </div>
</div>
</body>
</html>
