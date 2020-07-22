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

        switch ($tab) {     //Make Account Settings navbar visible

            case "viewAccountSettings":
            case "viewContactInfo":
            case "viewPaymentInfo":
            case "viewAccBalance":
            case "viewPasswordChange":
                echo "<script>document.getElementById('accSettingsNavbar').classList.remove('d-none');</script>";
                break;
        }

        switch ($tab) {
            case "viewJobs":  // view posted jobs
                $postedJobsData = getPostedJobsData();
                showPostedJobs($postedJobsData);
                break;
            case "postJob":  // post a job
                showPostJobForm();
                break;
            case "viewApplications":
                showApplications();
                break;
            case "viewContactInfo":
                showContactInfo();
                break;
            case "viewPaymentInfo":
                showPaymentInfo();
                break;
            case "viewAccBalance":
                showAccBalance();
                break;
            case "viewPasswordChange":
                showPasswordChange();
                break;

        }
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
//            header("Location: /GUI/employerDash.php?tab=viewJobs");
            break;
        case "postJob":
            echo "title:" . $_POST['title'] . "<br>";
            echo "category" . $_POST['category'] . "<br>";
            echo "description" . $_POST['description'] . "<br>";
            echo "numOpenings" . $_POST['numOpenings'] . "<br>";
            // TODO: database insert operation
//            header("Location: /GUI/employerDash.php?tab=viewJobs");
            break;
        case "viewApplications":
            $appID = $_REQUEST['appID'];
            $operation = $_REQUEST['op'];
            echo "operation: " . $operation . "<br>";
            echo "applicationID: " . $appID;
            changeApplicationStatus();  /* TODO: change application status */
//            header("Location: /GUI/employerDash.php?tab=viewApplications");
            break;
    }

}
/*********************** End of Controllers ******************************************************/



/************* Data access part *****************************************************************************/
// TODO: get posted jobs data from database
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
 *  "title": "abc",
 *  "datePosted": "2020-5-10",
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
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $postedJobsData[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $postedJobsData[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $postedJobsData[$i]['datePosted'] . "</p>" .
            "       <p><b>Category: </b>" . $postedJobsData[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $postedJobsData[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $postedJobsData[$i]['numOfOpenings'] . "</p>" .
            "       <p><a href='employerDash.php?tab=viewApplications&jobID=$ID'># Applications: " .
                        $postedJobsData[$i]['numOfApplications'] . "</a></p>" .
            "       <p><b># Hires: </b>" . /*TODO: $postedJobsData[$i]['numOfHires']" */ "To do". "</p>" .
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
            "           <form action='". $_SERVER['PHP_SELF']."?tab=viewApplications&appID=$appID"."' method='post'>".
            "           <button type='submit' name='op' value='deny' class='btn btn-warning'>Deny</button>" .
            "           <button type='submit' name='op' value='review' class='btn btn-secondary'>Review</button>" .
            "           <button type='submit' name='op' value='sendOffer' class='btn btn-primary'>Send Offer</button>" .
            "           <button type='submit' name='op' value='hire' class='btn btn-success'>Hire</button>" .
            "           <button type='submit' name='op' value='delete' class='btn btn-danger m-2'>Delete</button>" .
            "           </form>" .
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
function showPaymentInfo() {

    $html = "";

    for($i = 0; $i < 5 /*TODO: count of payment methods*/; $i++) {

        if(/*TODO: if credit card*/ $i<4) {

            $html = showCreditCardInfo($html/*, TODO: $CCNumber, CCExpiry*/);

        } else {

            $html = showDebitCardInfo($html /*, TODO: $bankAccountNumber*/);
        }
    }

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function showDebitCardInfo(string $html/*, TODO: $bankAccountNumber*/): string
{
    $isDefault = /*TODO: getDefaultPaymentMethod()*/ true;
    $bankAccountNumber = -1;
    $bankTransitNumber = -1;

    $html .=
        "<div class = 'row justify-content-center align-items-center' style='margin-left: 10px'>";

    if($isDefault == true) { // Make green border

        $html .=
            "<div class = 'col-8 border border-success rounded'>";
    } else { //make grey border

        $html .=
            "<div class = 'col-8 border rounded'>";
    }
    $html .=
        "     <p><b>Bank Account Number: </b>$bankAccountNumber</p>".
        "     <p><b>Bank Transit Number: </b>$bankTransitNumber</p>".
        "</div>";

//    $html .=
//        "              <div class='form-group'>" .
//        "                  <label for='baNumber'><b>Account number</b></label> " .
//        "                  <input type='text' class='form-control' placeholder='Enter account number' id='baNumber' name='baNumber' value=''>" .
//        "              </div>" .
//        "              <div class='form-group'>" .
//        "                   <label for='transitNumber'><b>Transit Number</b></label>" .
//        "                   <input type='text' class='form-control' placeholder='Enter transit number' id='transitNumber' name='transitNumber' value=''>" .
//        "              </div>" .
//        "   </div>";

    if($isDefault == false) {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "     <button class = 'btn btn-primary'>Set Default</button>" .
            "      <button class = 'btn btn-info' onclick='editDebitCard(/*TODO: $bankAccountNumber*/)'>Edit</button>" .
            "      <button class = 'btn btn-danger'>Delete</button>" .
            "</div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "     <button class = 'btn btn-info' onclick='editDebitCard(/*TODO: $bankAccountNumber*/)'>Edit</button>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showCreditCardInfo(string $html/*, TODO: $CCNumber, CCExpiry*/): string
{
    $isDefault = /*TODO: getDefaultPaymentMethod()*/ false;
    $CCName = "To do";
    $CCNumber = -1;
    $CCExpiry = -1;

    $html .=
        "<div class = 'row justify-content-center align-items-center' style='margin-left: 10px'>";

    if($isDefault == true) {  // Make green border

        $html .=
            "<div class = 'col-8 border border-success rounded'>";
    } else { // Make grey border

        $html .=
            "<div class = 'col-8 border rounded'>";
    }

    $html .=
        "     <p><b>Name on Card: </b>$CCName</p>".
        "     <p><b>Credit Card Number: </b>$CCNumber</p>".
        "     <p><b>Expiry Date: </b>$CCExpiry</p>".
        "</div>";

//    $html .=
//        "              <div class='form-group'>" .
//        "                  <label for='ccName'><b>Name</b></label>" .
//        "                  <input type='text' class='form-control' placeholder='Enter name' id='ccName' name='ccName' value=''>" .
//        "              </div>" .
//        "              <div class='form-group'>" .
//        "                   <label for='ccNumber'><b>Credit card number</b></label>" .
//        "                   <input type='text' class='form-control' placeholder='Enter card number' id='ccNumber' name='ccNumber' value=''>" .
//        "              </div>" .
//        "              <div class='form-group'>" .
//        "                   <label for='ccExpiration'><b>Expiration(MMYYYY)</b></label>" .
//        "                   <input type='text' class='form-control' placeholder='Enter expiration' id='ccExpiration' name='ccExpiration'value=''>" .
//        "              </div>" .
//        "</div>";

    if ($isDefault == false) {

        $html .=
            "   <div class = 'col-2 text-center'>" .
            "       <button class = 'btn btn-primary'>Set Default</button>" .
            "      <button class = 'btn btn-info' onclick='editCreditCard(/*, TODO: $CCNumber, CCExpiry*/)'>Edit</button>" .
            "       <button class = 'btn btn-danger'>Delete</button>" .
            "   </div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "     <button class = 'btn btn-info' onclick='editCreditCard(/*, TODO: $CCNumber, CCExpiry*/)'>Edit</button>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showContactInfo() {

    /* TODO: populate form */
    $html =
        "<div class = 'row justify-content-center'>" .
        "  <div class = 'col-8'>" .
        "           <form>" .
        "              <div class='form-group'>" .
        "                  <label for='eName'><b>Employer Name</b></label>" .
        "                  <input type='text' class='form-control' id='eName' placeholder='Enter employer name'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='name'><b>Representative Name</b></label>" .
        "                  <input type='text' class='form-control' id='name' placeholder='Enter representative name'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Representative email</b></label>" .
        "                  <input type='email' class='form-control' id='email' placeholder='Enter email'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Representative number</b></label>" .
        "                  <input type='text' class='form-control' id='number' placeholder='Enter phone number'>" .
        "              </div>" .
        "              <input class='btn btn-primary' type='submit' value='Submit'>".
        "           </form>" .
        "       </div>" .
        "  </div>" .
        "</div>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function showAccBalance() {

    $balance = 5; /*TODO: get balance*/

    $html = getBalanceHTML($balance);
    $html = getMonthlyPaymentRadioButtonsHTML($html);
    $html = getEmployerCategoryHTML($html);

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

/**
 * @param string $html
 * @return string
 */
function getEmployerCategoryHTML(string $html): string
{
    $toolTipEmpPrime = "You can post up to five jobs. A monthly charge of $50 will be applied.";
    $toolTipEmpGold = "You can post as many jobs as you like. A monthly charge of $100 will be applied";

    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Employer Category</b></div>" .
        "          <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "               <label class='btn btn-secondary' data-toggle='tooltip' data-placement='top' title='$toolTipEmpPrime'>" .
        "                    <input type='radio' name='options' id='employerPrime' autocomplete='off'> Employer Prime" .
        "               </label>" .
        "               <label class='btn btn-warning' data-toggle='tooltip' data-placement='top' title='$toolTipEmpGold'>" .
        "                    <input type='radio' name='options' id='employerGold' autocomplete='off'> Employer Gold" .
        "               </label>" .
        "          </div>" .
        "     </div>" .
        "</div>";
    return $html;
}

/**
 * @param string $html
 * @return string
 */
function getMonthlyPaymentRadioButtonsHTML(string $html): string
{
    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Payment Method</b></div>" .
        "          <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "               <label class='btn btn-info'>" .
        "                    <input type='radio' name='options' id='autoPayMonth' autocomplete='off'> Auto Monthly" .
        "               </label>" .
        "               <label class='btn btn-info'>" .
        "                    <input type='radio' name='options' id='manualPayMonth' autocomplete='off'> Manual Monthly" .
        "               </label>" .
        "          </div>" .
        "     </div>" .
        "</div>";

    return $html;
}

/**
 * @param float $balance
 * @return string
 */
function getBalanceHTML(float $balance): string
{
    $payment = /* TODO: get monthly payment */ 50;

    $html =
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-6'>";


    if ($balance >= 0) {

        $html .=
            "     <div><b>Account Balance:</b> $$balance</div>" .
            "     <div>Your account is in good standing.</div>".
            "</div>".
            "<div class ='col-2'>";
    } else {

        $html .=
            "     <div><span class='badge badge-danger'>Account Balance </span>$$balance</div>" .
            "     <div>Your account limited. Make a payment to gain full access</div>".
            "</div>".
            "<div class ='col-2'>";
    }

    if($balance < 0  || true /*TODO: || payment method == monthly manual*/) {

        $html .=
            "          <button class='btn btn-success' onclick = 'paymentApplied()'> Make Payment $$payment </button>".
            "     </div>".
            "</div>";
    } else { // balance in good standing and auto monthly payment

        $html .=
            "     </div>" .
            "</div>";
    }

    return $html;
}

function showPasswordChange() {

    $html =
        "<form>".
        "     <div class = 'row justify-content-center'>".
        "        <div class = 'col-8'>".
        "             <div class='form-group'>" .
        "                  <label for='prevPass'><b>Previous Password</b></label> " .
        "                  <input type='password' class='form-control' placeholder='Enter previous password' id='prevPass' name='prevPass' value=''>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='newPass'><b>New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Enter new password' id='newPass' name='newPass' value=''>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='conNewPass'><b>Confirm New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Confirm password' id='conNewPass' name='conNewPass' value=''>" .
        "              </div>" .
        "                   <input class='btn btn-primary' type='submit' value='Submit'>".
        "         </div>".
        "    </div>".
        "</form>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function goToPage($url) {
    echo "<script>window.location.href = '$url'</script>";
}

