<?php
session_start();

// first check login status, if not logged in, go to login page
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
    goToPage("/GUI/index.php");
}

$username = $_SESSION['username'];
$accountType = $_SESSION['accountType'];
echo "username: " . $username . "<br>";

/* Variable declaration */
$postedJobsData = array();


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
                showApplications($_GET['jobID']);
                break;
        }
    }
}

// post jobs
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];
    switch ($tab) {
        case "viewJobs":
            echo "deleteJobId: " . $_POST['deleteJobID'] . "<br>";
            // TODO: database delete operation
            break;
        case "postJob":
            echo "title:" . $_POST['title'] . "<br>";
            echo "category" . $_POST['category'] . "<br>";
            echo "description" . $_POST['description'] . "<br>";
            echo "numOpenings" . $_POST['numOpenings'] . "<br>";
            // TODO: database insert operation
            break;
    }

}

// show post job form in "postJob" tab
function showPostJobForm() {
    echo "<script>document.getElementById(\"viewJobs\").innerHTML = \"\";
    document.getElementById(\"postJob\").style.visibility = \"visible\";</script>";
}


// show posted jobs in "viewJobs" tab
function showPostedJobs($postedJobsData) {
    $html = "";
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


// TODO: get posted jobs data from database
function getPostedJobsData() {
    $data = array();
    $job1 = array("jobID" =>100, "title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>1,);
    $job2 = array("jobID" =>200, "title"=>"Job2", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description2", "numOfOpenings"=>2, "numOfApplications"=>2);
    $job3 = array("jobID" =>300, "title"=>"Job3", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description3", "numOfOpenings"=>3, "numOfApplications"=>3);
    $job4 = array("jobID" =>400, "title"=>"Job4", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description4", "numOfOpenings"=>4, "numOfApplications"=>4);
    array_push($data, $job1);
    array_push($data, $job2);
    array_push($data, $job3);
    array_push($data, $job4);
    return $data;
}

//$jobID == null -> viewAllApplications else viewApplications of jobID
function showApplications($jobID)
{
    $html = "";
    $jobID2 = $jobTitle = $datePosted = $appID = $appName = $appDate = $appStatus = "not yet set";
    $appStatus = "Denied/Under review/offer sent/accepted";

    if($jobID == 'null') {
        $html = viewAllApplications($jobID2, $jobTitle, $datePosted, $html, $appID, $appName, $appDate, $appStatus);
    } else {
        $html = viewApplicationsOfJob($jobID, $jobTitle, $datePosted, $html, $appID, $appName, $appDate, $appStatus);
    }

    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
}

function viewApplicationsOfJob($jobID, string $jobTitle, string $datePosted, string $html, string $appID, string $appName, string $appDate, string $appStatus): string
{
    $jobID = $_GET['jobID'];

    $html .=
        "<div class='row jobRow justify-content-center'>" .
        "     <div class='col-10 border text-center'>" .
        "         <p><b>Job ID:</b> $jobID <b>Job title:</b> $jobTitle <b>Date posted:</b> $datePosted </p>" .
        "     </div>" .
        "</div>";

    for ($applOfJob = 0; $applOfJob < 5/*TODO: total applicants of job*/; $applOfJob++) {
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

function viewAllApplications(string $jobID2, string $jobTitle, string $datePosted, string $html, string $appID, string $appName, string $appDate, string $appStatus): string
{
    for ($totEmpJobs = 0; $totEmpJobs < 3 /*TODO: total employer jobs*/; $totEmpJobs++) {
        $html .=
            "<div class='row jobRow justify-content-center'>" .
            "     <div class='col-10 border text-center'>" .
            "         <p><b>Job ID:</b> $jobID2 <b>Job title:</b> $jobTitle <b>Date posted:</b> $datePosted </p>" .
            "     </div>" .
            "</div>";
        for ($applOfJob = 0; $applOfJob < 5/*TODO: total applicants of job*/; $applOfJob++) {
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
    }
    return $html;
}

function goToPage($url) {
    echo "<script>window.location.href = '$url'</script>";
}

