<?php
include_once "db/db_config.php";

session_start();

// first check login status, if not logged in, go to login page
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
    goToPage("/GUI/index.php");
}

/*********** Data models variables ***********************************************************************/
$username = $_SESSION['username'];      // currently logged in user
$accountType = $_SESSION['accountType'];  // current user account type, (job seeker, employer, admin)
$maxSize = 5;

echo "username: $username&nbsp&nbsp&nbsp&nbsp";
echo "accountType: $accountType<br>";

/************** End of data models ************************************************************************/

/*********************** Controllers *********************************************************************/
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    require_once "../GUI/view/adminDashView.php";

    if(isset($_GET['tab'])) {

        $tab = $_GET['tab'];

        echo "$tab<br>";

        switch ($tab) {     //Make Account Settings navbar visible

            case "viewAccountSettings":
            case "viewContactInfo":
            case "viewPasswordChange":
                echo "<script>document.getElementById('accSettingsNavbar').classList.remove('d-none');</script>";
                break;
        }

        switch ($tab) {
            case "viewJobs":  // view posted jobs
                $postedJobsData = getPostedJobsData();
                showPostedJobs($postedJobsData);
                break;

            case "viewApplications":
                showApplications();
                break;

            case "viewAllUsers":
                $data = getAllUsers();
                showAllUsers($data);
                break;

            case "viewContactInfo":
                showContactInfo();
                break;

            case "viewPasswordChange":
                showPasswordChange();
                break;

        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];
    switch($tab) {
        case "deleteUser":
            echo "delete username: " . $_POST['username'] . "<br>";
            $deleteUsername = $_POST['username'];
            if (deleteUser($deleteUsername)) echo "operation success";
            else echo "operation failed";
            echo "<br>";
            echo "<a href='adminDash.php?tab=viewAllUsers'>view all users</a>";
            break;

        case "changeContactInfo":
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $number = $_POST['number'];
            echo "firstName: " .$firstName . "<br>";
            echo "lastName: " .$lastName . "<br>";
            echo "email: " .$email . "<br>";
            echo "number: " .$number . "<br>";
            if (changeContactInfo($username, $firstName, $lastName, $email, $number)) {
                echo "operation success. <br>";
            } else {
                echo "operation failed. <br>";
            }
            echo "<a href='adminDash.php?tab=viewContactInfo'>view contact info</a>";
            break;

        case "passwordChange":
            $prevPass = $_POST['prevPass'];
            $newPass = $_POST['newPass'];
            echo "previous Password: ". $_POST['prevPass'] . "<br>";
            echo "new Password: ". $_POST['newPass'] . "<br>";
            if (changePassword($prevPass, $newPass)) echo "operation success<br>";
            else echo "operation failed<br>";
            echo "<a href='seekerDash.php?tab=viewPasswordChange'>change password page</a>";
            break;
    }

}

//$jobID == null -> viewAllApplications else viewApplications of jobID
function showApplications()
{
    $html = "";
    if (!isset($_GET['jobID'])) {
        $jobsWithApplications = getAllApplications();  // get all applications data
        $html = viewAllApplications($jobsWithApplications); // show all applications, pass data to view.
    }
    else {
        $jobID = $_GET['jobID'];
        $job = getApplicationsByJobID($jobID);  // get one job, and its applications data
//        print_r($job);
        $html = viewApplicationsOfJob($job);    // show applications of this job
    }
    echo "<script>document.getElementById('viewApplications').innerHTML = \"". $html ."\"</script>";

}


/*********************** End of Controllers ******************************************************/


/************* Data access part *****************************************************************************/

// get posted jobs data from database
function getPostedJobsData() {
    $data = array();
    global $maxSize;

    $conn = connectDB();
    $sql = "select * from job limit $maxSize";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = array("jobID" =>$row["JobID"], "title"=>$row["Title"], "datePosted"=>$row["DatePosted"], "category"=>$row["Category"],
                "description"=>$row["Description"], "numOfOpenings"=>$row["EmpNeeded"]);
            $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
            $numOfApplications = getNumOfApplications($row["JobID"]);
            $numOfHires = getNumOfHires($row["JobID"]);
            $job["jobStatus"] = $jobStatus;
            $job["numOfApplications"] = $numOfApplications;
            $job["numOfHires"] = $numOfHires;
            array_push($data, $job);
        }
    }
    return $data;

}

// get number of applications of a job, given JobID
function getNumOfApplications($jobID) {
    $conn = connectDB();
    $sql = "select count(*) as n from application where JobID = $jobID";
    $result = $conn->query($sql);
    return $result->fetch_assoc()["n"];
}

// get number of hires of a job, given JobID
function getNumOfHires($jobID) {
    $conn = connectDB();
    $sql = "select count(*) as n from application where JobID = $jobID and ApplicationStatus = 'hired'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()["n"];
}

// get job by jobID, select * from Job where jobID = $jobID
/**
 * @param $jobID
 * @return array, job information
 * {
 *  "jobID": 1,
 *  "title": "abc",
 *  "datePosted": "2020-5-10",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 3
 * }
 */
function getJobByID($jobID) {
    global $username;
    $job = array();
    $conn = connectDB();
    $sql = "select * from job where JobID = $jobID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = array("jobID" => $row["JobID"], "title" => $row["Title"], "datePosted" => $row["DatePosted"], "category" => $row["Category"],
                "description" => $row["Description"], "numOfOpenings" => $row["EmpNeeded"]);
            $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
            $numOfApplications = getNumOfApplications($row["JobID"]);
            $numOfHires = getNumOfHires($row["JobID"]);
            $job["jobStatus"] = $jobStatus;
            $job["numOfApplications"] = $numOfApplications;
            $job["numOfHires"] = $numOfHires;
        }
    }
    return $job;
}

// get applications by job ID.
/**
 * @param $jobID
 * @return
 * {
 *  "jobID": 100,
 *  "title": "abc",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 3,
 *  "numOfHires" : 1,
 *  "applications":
 *    [
 *      {
 *          "appID" : 1,
 *          "appName": "Jack",
 *          "appDate": "2020-7-20",
 *          "appStatus": "Accepted"
 *      },
 *      {
 *          ...
 *      },
 *      ...
 *    ]
 * }
 */
function getApplicationsByJobID($jobID) {
    $job = getJobByID($jobID);
    $applications = array();
    $conn = connectDB();
    $sql = "select * from application where JobID = $jobID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $app = array("appName"=>$row["ApplicantUserName"], "appDate"=>$row["ApplicationDate"], "appStatus"=>$row["ApplicationStatus"]);
            array_push($applications, $app);
        }
    }
    $job["applications"] = $applications;

    return $job;
}

// get all applications
/**
 * return all jobs with applications information
 * {
 *  "jobID": 100,
 *  "title": "abc",
 *  "datePosted": "2020-5-10",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 3,
 *  "numOfHires" : 1,
 *  "jobStatus": open/close,
 *  "applications":
 *   [
 *      {
 *          "appID" : 1,
 *          "appName": "Jack",
 *          "appDate": "2020-6-20",
 *          "appStatus": "Accepted",
 *      },
 *      {
 *          ...
 *      },
 *      ...
 *   ]
 * },
 * {
 *    ...
 * },
 * ...
 */
function getAllApplications() {
    $jobs = getPostedJobsData();
    $data = array();
    for ($i = 0; $i < count($jobs); $i++) {
        $jobID = $jobs[$i]["jobID"];
        $job = getApplicationsByJobID($jobID);
        array_push($data, $job);
    }
    return $data;
}

// get all users
function getAllUsers() {
    global $maxSize;
    $employers = array();
    $applicants = array();

    $conn = connectDB();
    $sql = "select UserName, FirstName, LastName, Email,  ContactNumber, EmployerName
            from employer natural join user
            limit $maxSize";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $e = array("username"=>$row["UserName"], "empName"=>$row["EmployerName"], "firstName"=>$row["FirstName"], "lastName"=>$row["LastName"],
                "email"=>$row["Email"], "number"=>$row["ContactNumber"]);
            array_push($employers, $e);
        }
    }

    $conn2 = connectDB();
    $sql2 = "select UserName, FirstName, LastName, Email,  ContactNumber
            from applicant natural join user
            limit $maxSize";
    $result2 = $conn2->query($sql2);
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $a = array("username"=>$row2["UserName"], "firstName"=>$row2["FirstName"], "lastName"=>$row2["LastName"],
                "email"=>$row2["Email"], "number"=>$row2["ContactNumber"]);
            array_push($applicants, $a);
        }
    }
    return [$employers, $applicants];
}

// delete user
function deleteUser($deleteUsername) {
    $conn = connectDB();
    $sql = "delete from user where UserName = '$deleteUsername'";
    if (mysqli_query($conn, $sql)) return true;
    return false;
}

function changeContactInfo($username, $firstName, $lastName, $email, $number) {
    $conn = connectDB();
    $sql = "update user set FirstName = '$firstName', LastName = '$lastName', Email = '$email', ContactNumber = '$number'
            where UserName = '$username'";
    if (mysqli_query($conn, $sql)) return true;
    return false;
}

function changePassword($prevPass, $newPass) {
    global $username;
    $conn = connectDB();
    $result = mysqli_query($conn, "select Password from user where UserName = '$username'");
    if ($result->fetch_assoc()['Password'] !== $prevPass) {
        echo "<script>alert('previous password not correct')</script>";
        return false;
    } else {
        $conn2 = connectDB();
        $sql = "update user set Password = '$newPass' where UserName = '$username'";
        if (mysqli_query($conn2, $sql)) return true;
        return false;
    }
}

/************************* End of data access *****************************************************/


/****************** Front-end view part ******************************************************/
function showPostedJobs($postedJobsData) {
//    $html =
//        "<div class='row justify-content-center'>".
//        "    <div class = 'col-3'>".
//        "       <div class='form-group text-center'>" .
//        "            <label for='selectCategory'>Select category:</label>" .
//        "            <select class='form-control'' id='selectCategory'>" .
//        "                 <option>...</option>";
//
//    for($i = 0; $i < 5 /*TODO: count of categories*/; $i++) {
//
//        $category = "category";
//        $html .=
//            "                 <option>$category</option>";
//    }
//    $html .=
//        "            </select>" .
//        "      </div>".
//        "   </div>".
//        "</div>";
    $html = "";

    for ($i = 0; $i < count($postedJobsData); $i++) {

        $ID = $postedJobsData[$i]['jobID'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $postedJobsData[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $postedJobsData[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $postedJobsData[$i]['datePosted'] . "</p>" .
            "       <p><b>Category: </b>" . $postedJobsData[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $postedJobsData[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $postedJobsData[$i]['numOfOpenings'] . "</p>" .
            "       <p><a href='".$_SERVER['PHP_SELF']."?tab=viewApplications&jobID=$ID'># Applications: " .
            $postedJobsData[$i]['numOfApplications'] . "</a></p>" .
            "       <p><b># Hires: </b>" . $postedJobsData[$i]['numOfHires'] . "</p>" .
            "       <p><b>Job Status: </b>" . $postedJobsData[$i]['jobStatus']. "</p>" .
            "    </div>" .
            "    <div class='col-2 justify-content-center text-center'>" .
//            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs&jobID=$ID' method='post'>" .
//            "       <button type='submit' name='op' value='open' class='btn btn-success'> Open </button>" .
//            "    </form>" .
//            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs&jobID=$ID' method='post'>" .
//            "       <button type='submit' name='op' value='close' class='btn btn-warning'> Close </button>" .
//            "    </form>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs&jobID=$ID' method='post' onsubmit='return deleteJob(" . $ID . ")'>" .
            "       <button type='submit' name='op' value='delete' class='btn btn-danger'> Delete </button>" .
            "    </form>" .
            "    </div>" .
            "</div>";


    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";

//    echo "<script>document.getElementById('viewJobs').innerHTML = \"<h1>Hello</h1>\"</script>";
}

function viewApplicationsOfJob($job)
{
    $html = "";
    $jobID = $job["jobID"];
    $jobTitle = $job["title"];
    $datePosted = $job["datePosted"];
    $numOfApps = $job["numOfApplications"];
    $apps = $job["applications"];
    $html .=
        "<div class='row jobRow justify-content-center'>" .
        "     <div class='col-10 border text-center'>" .
        "         <p><b>Job ID:</b> $jobID <b>Job title:</b> $jobTitle <b>Date posted:</b> $datePosted </p>" .
        "     </div>" .
        "</div>";

    for ($i = 0; $i < $numOfApps; $i++) {
        $appName = $apps[$i]['appName'];
        $appDate = $apps[$i]['appDate'];
        $appStatus = $apps[$i]['appStatus'];
        $html .=
            "<div class='row applicantRow justify-content-center'>" .
            "      <div class='col-6 border'>" .
            "           <p><b>Applicant Name:</b> $appName</p>" .
            "           <p><b>Application Date:</b> $appDate</p>" .
            "           <p><b>Status:</b> $appStatus</p>" .
            "     </div>" .
            "</div>";
    }
    return $html;
}

function showContactInfo() {
    $url = $_SERVER['PHP_SELF']."?tab=changeContactInfo";

    $html =
        "<div class = 'row justify-content-center'>" .
        "  <div class = 'col-8'>" .
        "           <form action='$url' method='post'>" .
        "              <div class='form-group'>" .
        "                  <label for='firstName'><b>First Name</b></label>" .
        "                  <input type='text' class='form-control' id='firstName' name='firstName' placeholder='Enter first name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='lastName'><b>Last Name</b></label>" .
        "                  <input type='text' class='form-control' id='lastName' name='lastName' placeholder='Enter last name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Email</b></label>" .
        "                  <input type='email' class='form-control' id='email' name='email' placeholder='Enter email' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Number</b></label>" .
        "                  <input type='text' class='form-control' id='number' name='number' placeholder='Enter phone number' required>" .
        "              </div>" .
        "              <input class='btn btn-primary' type='submit' value='Submit'>".
        "           </form>" .
        "       </div>" .
        "  </div>" .
        "</div>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function showPasswordChange() {

    $html =
        "<form action='".$_SERVER['PHP_SELF']."?tab=passwordChange' method='post' onsubmit='return confirmPassword()'>".
        "     <div class = 'row justify-content-center'>".
        "        <div class = 'col-8'>".
        "             <div class='form-group'>" .
        "                  <label for='prevPass'><b>Previous Password</b></label> " .
        "                  <input type='password' class='form-control' placeholder='Enter previous password' id='prevPass' name='prevPass' value='' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='newPass'><b>New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Enter new password' id='newPass' name='newPass' value='' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='conNewPass'><b>Confirm New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Confirm password' id='conNewPass' name='conNewPass' value='' required>" .
        "              </div>" .
        "                   <input class='btn btn-primary' type='submit' value='Submit'>".
        "         </div>".
        "    </div>".
        "</form>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}


function showAllUsers($data) {

    $html = "";

    $employers = $data[0];
    $applicants = $data[1];

    for($i = 0; $i < count($employers); $i++) {
        $html = viewEmpContInfo($html, $employers[$i]);
    }

    for ($j = 0; $j < count($applicants); $j++) {
        $html = viewSeekerContInfo($html, $applicants[$j]);
    }
    echo "<div class='container'>".$html. "</div>";
}

function viewEmpContInfo($html , $data){

    $username = $data["username"];
    $empName = $data["empName"];
    $empRepName = $data["firstName"] . " " . $data["lastName"];
    $empRepEmail = $data["email"];
    $empRepNumber = $data["number"];
    $html .=
        "<div class = 'row justify-content-center align-items-center'>" .
        "     <div class = 'col-8 border rounded border-dark'>" .
        "          <p><b>Employer Name: </b> $empName</p>" .
        "          <p><b>Representative Name: </b> $empRepName</p>" .
        "          <p><b>Representative Email: </b> $empRepEmail</p>" .
        "          <p><b>Representative Number: </b>$empRepNumber</p>" .
        "     </div>".
        "     <div class='col-2 d-flex justify-content-center '>" .
        "          <form action='".$_SERVER['PHP_SELF']."?tab=deleteUser' method='post' onsubmit='return confirm(\"Sure to delete this user?\")'>" .
        "               <button type='submit' name='' value='' class='btn btn-info'> Activate </button>" .
        "               <button type='submit' name='' value='' class='btn btn-warning'> Deactivate </button>" .
        "               <button type='submit' name='username' value='$username' class='btn btn-danger'> Delete </button>" .
        "          </form>" .
        "     </div>" .
//        "         <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
//        "              <label class='btn btn-success'>" .
//        "                   <input type='radio' name='options' id='activate' autocomplete='off'> Activate" .
//        "              </label>" .
//        "              <label class='btn btn-warning'>" .
//        "                   <input type='radio' name='options' id='deactivate' autocomplete='off'> Deactivate" .
//        "              </label>" .
//        "         </div>".
        "</div>";

    return $html;
}

function viewSeekerContInfo($html, $data) {

    $username = $data["username"];
    $seekerName = $data["firstName"] . " " . $data["lastName"];
    $seekerEmail = $data["email"];
    $seekerNumber = $data["number"];
    $html .=
        "<div class = 'row justify-content-center align-items-center'>" .
        "     <div class = 'col-8 border rounded border-dark'>" .
        "          <p><b>Job Seeker Name: </b> $seekerName</p>" .
        "          <p><b>Job Seeker Email: </b> $seekerEmail</p>" .
        "          <p><b>Job Seeker Number: </b>$seekerNumber</p>" .
        "     </div>".
        "    <div class='col-2 d-flex justify-content-center '>" .
        "         <form action='".$_SERVER['PHP_SELF']."?tab=deleteUser' method='post' onsubmit='return confirm(\"Sure to delete this user?\")'>" .
        "               <button type='submit' name='' value='' class='btn btn-info'> Activate </button>" .
        "               <button type='submit' name='' value='' class='btn btn-warning'> Deactivate </button>" .
        "            <button type='submit' name='username' value='$username' class='btn btn-danger'> Delete </button>" .
        "         </form>" .
        "    </div>" .
//        "         <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
//        "              <label class='btn btn-success'>" .
//        "                   <input type='radio' name='options' id='activate' autocomplete='off'> Activate" .
//        "              </label>" .
//        "              <label class='btn btn-warning'>" .
//        "                   <input type='radio' name='options' id='deactivate' autocomplete='off'> Deactivate" .
//        "              </label>" .
//        "         </div>".
    "</div>";

    return $html;
}