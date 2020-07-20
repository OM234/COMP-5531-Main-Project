<?php
session_start();

// first check login status, if not logged in, go to login page
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
    goToPage("/GUI/index.php");
}



/*********** Data models variables ***********************************************************************/

$username = $_SESSION['username'];      // currently logged in user
$accountType = $_SESSION['accountType'];  // current user account type
echo "username: " . $username . "<br>";

$postedJobsData = array();   // posted Job data
$jobsWithApplications = array();  // posted Job with application data

/************** End of data models ************************************************************************/




/*********************** Controllers *********************************************************************/
// if already logged in, check tab parameter to decide which part to shown in this page
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    require_once "../GUI/view/employerDashView.php";

    if(isset($_GET['tab'])) {

        $tab = $_GET['tab'];

        echo "$tab<br>";

        switch ($tab) {
            case "viewJobs":  // view posted jobs
                $postedJobsData = getPostedJobsData();
                showPostedJobs($postedJobsData);
                break;
            case "postJob":  // post a job
                showPostJobForm();
                break;
            case "viewApplications":
                $jobID = $_GET['jobID'];
                showApplications($jobID);
                break;
        }
    }
}

//$jobID == null -> viewAllApplications else viewApplications of jobID
function showApplications($jobID)
{
    $html = "";
    if ($jobID == 'null') {
        $jobsWithApplications = getAllApplications();  // get all applications data
        $html = viewAllApplications($jobsWithApplications); // show all applications, pass data to view.
    }
    else {
        $job = getApplicationsByJobID($jobID);  // get one job, and its applications data
        $html = viewApplicationsOfJob($job);    // show applications of this job
    }
    echo "<script>document.getElementById('viewApplications').innerHTML = \"". $html ."\"</script>";

}



// post jobs
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];
    switch ($tab) {
        case "viewJobs":
            echo "deleteJobId: " . $_POST['deleteJobID'] . "<br>";
            // TODO: database delete operation
            header("Location: /GUI/employerDash.php?tab=viewJobs");
            break;
        case "postJob":
            echo "title:" . $_POST['title'] . "<br>";
            echo "category" . $_POST['category'] . "<br>";
            echo "description" . $_POST['description'] . "<br>";
            echo "numOpenings" . $_POST['numOpenings'] . "<br>";
            // TODO: database insert operation
            header("Location: /GUI/employerDash.php?tab=viewJobs");
            break;
        case "viewApplications":
            changeApplicationStatus();
            break;
    }

}
/*********************** End of Controllers ******************************************************/



/************* Data access part *****************************************************************************/
// TODO: get all posted jobs data from database
/**
 * @return array
 * {
 *  "jobID": 1,
 *  "title": "abc",
 *  "datePosted": "2020-5-10",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 10
 * },
 * {
 *   ...
 * },
 * ...
 */
function getPostedJobsData() {
    $data = array();
    $job1 = array("jobID" =>100, "title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>3,);
    $job2 = array("jobID" =>200, "title"=>"Job2", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description2", "numOfOpenings"=>2, "numOfApplications"=>3);
    $job3 = array("jobID" =>300, "title"=>"Job3", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description3", "numOfOpenings"=>3, "numOfApplications"=>3);
    $job4 = array("jobID" =>400, "title"=>"Job4", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description4", "numOfOpenings"=>4, "numOfApplications"=>3);
    array_push($data, $job1);
    array_push($data, $job2);
    array_push($data, $job3);
    array_push($data, $job4);
    return $data;
}

// TODO: get job by jobID, select * from Job where jobID = $jobID
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
    $job1 = array("jobID" =>100, "title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>3,);
    return $job1;
}


// TODO: get all applications
/**
 * return all jobs with applications information
 * {
 *  "jobID": 100,
 *  "title": "abc"*  "datePosted": "2020-5-10",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 3,
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
    $allJobsWithApplications = getPostedJobsData();
    for ($i = 0; $i < count($allJobsWithApplications); $i++) {
        $applications = array();
        $application1 = array("appID"=>1, "appName"=>"Jack", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
        $application2 = array("appID"=>2, "appName"=>"Alice", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
        $application3 = array("appID"=>3, "appName"=>"Michael", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
        array_push($applications, $application1);
        array_push($applications, $application2);
        array_push($applications, $application3);
        $allJobsWithApplications[$i]["applications"] = $applications;
        $allJobsWithApplications[$i]["numOfApplications"] = 3;
    }
    return $allJobsWithApplications;
}

// TODO: get applications by job ID.
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
 *  "applications": {
 *      "appID" : 1,
 *      "appName": "Jack",
 *      "appDate": "2020-7-20",
 *      "appStatus": "Accepted"
 *   }
 * }
 */
function getApplicationsByJobID($jobID) {
    $job = getJobByID($jobID);
    $applications = array();
    $application1 = array("appID"=>1, "appName"=>"Jack", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
    $application2 = array("appID"=>2, "appName"=>"Alice", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
    $application3 = array("appID"=>3, "appName"=>"Michael", "appDate"=>date("Y-m-d"), "appStatus"=>"Accepted");
    array_push($applications, $application1);
    array_push($applications, $application2);
    array_push($applications, $application3);
    $job["applications"] = $applications;
    $job["numOfApplications"] = 3;
    return $job;
}



/************************* End of data access *****************************************************/





/****************** Front-end view part ******************************************************/
// show post job form in "postJob" tab
function showPostJobForm() {
    echo "<script>document.getElementById(\"viewJobs\").innerHTML = \"\";
    document.getElementById(\"postJob\").style.visibility = \"visible\";</script>";
}


// show posted jobs in "viewJobs" tab
function showPostedJobs($postedJobsData) {
    $html =
        "<div class='row justify-content-center'>".
        "    <div class = 'col-3'>".
        "       <div class='form-group text-center'>" .
        "            <label for='selectCategory'>Select category:</label>" .
        "            <select class='form-control'' id='selectCategory'>" .
        "                 <option>...</option>";

    for($i = 0; $i < 5 /*TODO: count of categories*/; $i++) {

        $category = "category";
        $html .=
        "                 <option>$category</option>";
    }
    $html .=
        "            </select>" .
        "      </div>".
        "   </div>".
        "</div>";

    for ($i = 0; $i < count($postedJobsData); $i++) {

        $ID = $postedJobsData[$i]['jobID'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border'>" .
            "       <p class='jobTitle'><b>" . $postedJobsData[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $postedJobsData[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $postedJobsData[$i]['datePosted'] . "</p>" .
            "       <p><b>Category: </b>" . $postedJobsData[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $postedJobsData[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $postedJobsData[$i]['numOfOpenings'] . "</p>" .
            "       <p><a href='employerDash.php?tab=viewApplications&jobID=$ID'># Applications: " .
                        $postedJobsData[$i]['numOfApplications'] . "</a></p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs' method='post' onsubmit='return deleteJob(" . $ID . ")'>" .
            "       <button type='submit' name='deleteJobID' value='" . $ID . "' class='btn btn-danger'> Delete </button>" .
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
        $appID = $apps[$i]['appID'];
        $appName = $apps[$i]['appName'];
        $appDate = $apps[$i]['appDate'];
        $appStatus = $apps[$i]['appStatus'];
        $html .=
            "<div class='row applicantRow justify-content-center'>" .
            "      <div class='col-6 border'>" .
            "           <p><b>Applicant ID:</b> $appID</p>" .
            "           <p><b>Applicant Name:</b> $appName</p>" .
            "           <p><b>Application Date:</b> $appDate</p>" .
            "           <p><b>Status:</b> $appStatus</p>" .
            "     </div>" .
            "     <div class='col-4 text-center my-auto'>" .
            "           <button class='btn btn-warning'>Deny</button>" .
            "           <button class='btn btn-secondary'>Review</button>" .
            "           <button class='btn btn-primary'>Send Offer</button>" .
            "           <button class='btn btn-success'>Hire</button>" .
            "           <button class='btn btn-danger m-2'>Delete</button>" .
            "    </div>" .
            "</div>";
    }
    return $html;
}

function viewAllApplications($jobs)
{
    $html = "";
    for ($i = 0; $i < count($jobs); $i++) {
        $html .= viewApplicationsOfJob($jobs[$i]);
    }

    return $html;
}

function goToPage($url) {
    echo "<script>window.location.href = '$url'</script>";
}

