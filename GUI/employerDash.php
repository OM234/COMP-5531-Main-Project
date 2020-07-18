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
        }
    }
}

// post jobs
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    echo $_REQUEST['tab'] . "<br>";
    echo $_POST['title'] . "<br>";
    echo $_POST['category'] . "<br>";
    echo $_POST['description'] . "<br>";
    echo $_POST['numOpenings'] . "<br>";

    // TODO: database insert operation

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
        $html .=
            "<div class='row justify-content-center'>".
            "    <div class='col-10 border'>".
            "       <p class='jobTitle'><b>". $postedJobsData[$i]['title'] ."</b></p><br>".
            "       <p><b>Date Posted: </b>". $postedJobsData[$i]['datePosted'] ."</p>".
            "       <p><b>Category: </b>". $postedJobsData[$i]['category'] ."</p>".
            "       <p><b>Description: </b>". $postedJobsData[$i]['description'] ."</p>".
            "       <p><b># Openings: </b>". $postedJobsData[$i]['numOfOpenings'] ."</p>".
            "       <p><b># Applications: </b>". $postedJobsData[$i]['numOfApplications'] ."</p>".
            "    </div>".
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
//    echo "<script>document.getElementById('viewJobs').innerHTML = \"<h1>Hello</h1>\"</script>";
}


// TODO: get posted jobs data from database
function getPostedJobsData() {
    $data = array();
    $job1 = array("title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>1);
    $job2 = array("title"=>"Job2", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description2", "numOfOpenings"=>2, "numOfApplications"=>2);
    $job3 = array("title"=>"Job3", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description3", "numOfOpenings"=>3, "numOfApplications"=>3);
    $job4 = array("title"=>"Job4", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description4", "numOfOpenings"=>4, "numOfApplications"=>4);
    array_push($data, $job1);
    array_push($data, $job2);
    array_push($data, $job3);
    array_push($data, $job4);
    return $data;
}


function goToPage($url) {
    echo "<script>window.location.href = '$url'</script>";
}

