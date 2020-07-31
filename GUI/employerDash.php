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
$userCategory = getUserCategory($username);  // current user's category, gold, prime
$autoPay = getAutoOrManual($username);    // auto payment or maunal payment, true for auto.
$accountStatus = getAccountStatus($username);  // get account status, true(active), false(not active)
$accountBalance = getAccountBalance($username);  // get account balance
$monthlyCharge= getMonthlyCharge($userCategory);
$jobCategories = getJobCategoriesByUsername($username); // get job categories, Technical ...
$_SESSION['jobcategories'] = $jobCategories;  // for cross file data transfer

echo "username: $username &nbsp&nbsp&nbsp&nbsp";
echo "category: $userCategory&nbsp&nbsp&nbsp&nbsp";
echo "autoPayment: $autoPay&nbsp&nbsp&nbsp&nbsp";
echo "accountStatus: $accountStatus&nbsp&nbsp&nbsp&nbsp";
echo "<br>";

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
            case "signout":
                session_destroy();
                break;
            case "viewJobs":  // view posted jobs
                if ($accountStatus) {
                    $postedJobsData = getPostedJobsData();
                    showPostedJobs($postedJobsData);
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "postJob":  // post a job
                if ($accountStatus) {
                    $categories = getJobCategoriesByUsername($username);
                    showPostJobForm();
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "viewApplications":
                if ($accountStatus) {
                    showApplications();
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "viewContactInfo":
                showContactInfo();
                break;
            case "viewPaymentInfo":
                $paymentInfo = getPaymentInfo();  // get payment info data
                showPaymentInfo($paymentInfo);    // show payment info
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
            $data = array();
            $data["title"] = $_POST['title'];
            $data["category"] = $_POST['category'];
            $data["description"] = $_POST['description'];
            $data["numOpenings"] = $_POST['numOpenings'];
            echo "title:" . $_POST['title'] . "<br>";
            echo "category" . $_POST['category'] . "<br>";
            echo "description" . $_POST['description'] . "<br>";
            echo "numOpenings" . $_POST['numOpenings'] . "<br>";
            if (insertJob($data)) {
                echo "Insert successfully";
            } else {
                echo "Insert failed";
            }
            echo "<br><br><a href='/GUI/employerDash.php?tab=viewJobs'>view jobs</a>";
            break;
        case "viewApplications":
            $appName = $_REQUEST['appName'];
            $jobID = $_REQUEST['jobID'];
            $operation = $_REQUEST['op'];
            echo "operation: " . $operation . "<br>";
            echo " application name: " . $appName;
            echo " jobID: " . $jobID;
            changeApplicationStatus();  /* TODO: change application status */
//            header("Location: /GUI/employerDash.php?tab=viewApplications");
            break;

        case "changeAccBalance":
            if (isset($_POST['upgrade'])) echo "upgrade to: ". $_POST['upgrade'] . "<br>" ;
            if (isset($_POST['downgrade'])) echo "downgrade to: ". $_POST['downgrade'] . "<br>" ;
            if (isset($_POST['auto'])) echo "Change auto payment to auto? : ". $_POST['auto'] . "<br>" ;
            break;

        case "passwordChange":
            echo "previous Password: ". $_POST['prevPass'] . "<br>";
            echo "new Password: ". $_POST['newPass'] . "<br>";
            break;

        case "addCreditCard":
            echo "ccName: " .$_POST['ccName'] . "<br>";
            echo "ccNumber: " .$_POST['ccNumber'] . "<br>";
            echo "ccvNumber: " .$_POST['ccvNumber'] . "<br>";
            echo "ccExpiration: " .$_POST['ccExpiration'] . "<br>";
            break;

        case "addDebitCard":
            echo "baNumber: " .$_POST['baNumber'] . "<br>";
            echo "transitNumber: " .$_POST['transitNumber'] . "<br>";
            break;

        case "changeContactInfo":
            echo "eName: " .$_POST['eName'] . "<br>";
            echo "firstName: " .$_POST['firstName'] . "<br>";
            echo "lastName: " .$_POST['lastName'] . "<br>";
            echo "email: " .$_POST['email'] . "<br>";
            echo "number: " .$_POST['number'] . "<br>";
            break;

        case "makePayment":
            echo "payment Amount: " .$_POST['amount'] ."<br>";
            // TODO: add account balance, sql query.
            break;

    }

}
/*********************** End of Controllers ******************************************************/



/************* Data access part *****************************************************************************/
// Get user's category, gold/prime
function getUserCategory($username) {
    $conn = connectDB();
    $sql = "select Category from employer where UserName = '$username'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['Category'];
}

// TODO: get user's payment method, auto or manual, return true for auto, false for manual.
function getAutoOrManual($username) {
    return true;
}

// Get user's account status, true for active, false for freeze
function getAccountStatus($username) {
    $balance = getAccountBalance($username);
    return $balance >= 0;
}

// Get user's account balance
function getAccountBalance($username) {
    $conn = connectDB();
    $sql = "select Balance from employer where UserName = '$username'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['Balance'];
}

// Get monthly payment for different user category
function getMonthlyCharge($userCategory) {
    if ($userCategory === 'gold') {
        return 100;
    } else if ($userCategory === 'prime') {
        return 50;
    }
}


// get posted jobs data from database
function getPostedJobsData() {
    global $username;
    $data = array();

    $conn = connectDB();
    $sql = "select * from job where EmployerUserName = '$username'";
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

// get applications data of a job, given jobID


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


/**
 * @return array[]: [creditCardInfo, debitCardInfo]
 * creditCardInfo :
 * [
 *   {
 *      "CCNumber" : "123456",
 *      "CCExpiry" : "2020-8-1",
 *      "CCName" : "Alice",
 *      "CCV": "123",
 *      "isDefault" : false
 *   },
 *   {
 *     ...
 *   },
 *  ...
 * ]
 *
 * debitCardInfo:
 * [
 *   {
 *      "bankAccountNumber" : "123456789",
 *      "bankTransitNumber" : "TDB",
 *      "isDefault" : false
 *   },
 *   {
 *      ...
 *   }
 *  ...
 * ]
 */

function getPaymentInfo() {
    global $username;

    $creditCardInfo = getCreditCardInfo($username);

    $debitCardInfo = getDebitCardInfo($username);

    return [$creditCardInfo, $debitCardInfo];
}

// get credit card info
function getCreditCardInfo($username) {
    $creditCardInfo = array();

    $conn = connectDB();
    $sql = "select *
            from
            (select UserName, CCNumber
            from employer, employercc
            where employer.UserName = employercc.EmployerUserName) as T natural join creditcardinfo
            where UserName = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cci = array("CCNumber"=>$row["CCNumber"], "CCExpiry"=>$row["ExpireDate"], "CCBNumber"=>$row["CCBNumber"],
                "isDefault"=>$row["IsDefault"], "autoManual"=>$row["Auto_Manual"]);
            array_push($creditCardInfo, $cci);
        }
    }
    return $creditCardInfo;
}

// get debit card info
function getDebitCardInfo($username) {
    $debitCardInfo = array();

    $conn = connectDB();
    $sql = "select * from
            (select UserName, AccountNumber
                from employer, employerpad
                where employer.UserName = employerpad.EmployerUserName) as T natural join padinfo
            where UserName = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dci = array("accountNumber"=>$row["AccountNumber"], "instituteNumber"=>$row["InstituteNumber"],
                "branchNumber"=>$row["BranchNumber"], "isDefault"=>$row["isDefault"], "autoManual"=>$row["Auto_Manual"]);
            array_push($debitCardInfo, $dci);
        }
    }
    return $debitCardInfo;
}

// get all distinct job categories by username
function getJobCategoriesByUsername($username) {
    $categories = array();

    $conn = connectDB();
    $sql = "select distinct Category from job where EmployerUserName = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($categories, $row["Category"]);
        }
    }
    return $categories;
}

// insert a job into database, return true if insert successfully.
function insertJob($data) {
    global $username;
    $title =$data["title"];
    $description = $data["description"];
    $dateposted = date("Y-m-d");
    $category = $data["category"];
    $jobStatus = 1;
    $numOpenings = $data["numOpenings"];
    $conn = connectDB();
    $sql = "insert into job 
            (EmployerUserName, Title, DatePosted, Description, Category, JobStatus, EmpNeeded) 
            VALUES
            ('$username', '$title', '$dateposted', '$description', '$category', $jobStatus, $numOpenings)";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    else {
        return false;
    }
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
            "       <p><b># Hires: </b>" . $postedJobsData[$i]['numOfHires'] . "</p>" .
            "       <p><b>Job Status: </b>" . $postedJobsData[$i]['jobStatus']. "</p>" .
            "    </div>" .
            "    <div class='col-2 justify-content-center text-center'>" .
            "         <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
            "              <label class='btn btn-success'>" .
            "                   <input type='radio' name='options' id='openJob' autocomplete='off'> Open" .
            "              </label>" .
            "              <label class='btn btn-warning'>" .
            "                   <input type='radio' name='options' id='closeJob' autocomplete='off'> Close" .
            "              </label>" .
            "         </div>" .
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
            "     <div class='col-4 text-center my-auto'>" .
            "           <form action='". $_SERVER['PHP_SELF']."?tab=viewApplications&appName=$appName&jobID=$jobID"."' method='post'>".
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
function showPaymentInfo($paymentInfo) {

    $creditCardInfo = $paymentInfo[0];
    $debitCardInfo = $paymentInfo[1];

    $html =
        "<div class = 'row justify-content-center align-items-center'>".
        "     <div class = 'col-8 text-center'>".
        "          <button class = 'btn btn-success' onclick='editCreditCard()'>Add Credit Card</button>" .
        "          <button class = 'btn btn-success' onclick='editDebitCard()'>Add Bank Card</button>" .
        "     </div>".
        "</div>";


    for($i = 0; $i < count($creditCardInfo); $i++) {

        $html = showCreditCardInfo($html, $creditCardInfo[$i]);

    }

    for ($i = 0; $i < count($debitCardInfo); $i++) {

        $html = showDebitCardInfo($html, $debitCardInfo[$i]);

    }

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function showDebitCardInfo(string $html, $data): string
{
    $isDefault = $data["isDefault"];
    $accountNumber = $data["accountNumber"];
    $instituteNumber = $data["instituteNumber"];
    $branchNumber = $data["branchNumber"];

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
        "     <p><b>Bank Account Number: </b>$accountNumber</p>".
        "     <p><b>Institute Number: </b>$instituteNumber</p>".
        "     <p><b>Branch Number: </b>$branchNumber</p>".
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
            "      <button class = 'btn btn-info' onclick='editDebitCard(/*TODO: $accountNumber*/)'>Edit</button>" .
            "      <button class = 'btn btn-danger'>Delete</button>" .
            "</div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "     <button class = 'btn btn-info' onclick='editDebitCard(/*TODO: $accountNumber*/)'>Edit</button>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showCreditCardInfo(string $html, $data): string
{
    $isDefault = $data["isDefault"];
    $CCNumber = $data["CCNumber"];
    $CCExpiry = $data["CCExpiry"];
    $CCBNumber = $data["CCBNumber"];

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
        "     <p><b>Credit Card Number: </b>$CCNumber</p>".
        "     <p><b>Expiry Date: </b>$CCExpiry</p>".
        "     <p><b>CCB Number: </b>$CCBNumber</p>".
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
            "       <button class = 'btn btn-info' onclick='editCreditCard(/*, TODO: $CCNumber, CCExpiry*/)'>Edit</button>" .
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
    $url = $_SERVER['PHP_SELF']."?tab=changeContactInfo";

    /* TODO: populate form */
    $html =
        "<div class = 'row justify-content-center'>" .
        "  <div class = 'col-8'>" .
        "           <form action='$url' method='post'>" .
        "              <div class='form-group'>" .
        "                  <label for='eName'><b>Employer Name</b></label>" .
        "                  <input type='text' class='form-control' id='eName' name='eName' placeholder='Enter employer name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='name'><b>Representative First Name</b></label>" .
        "                  <input type='text' class='form-control' id='firstName' name='firstName' placeholder='Enter representative name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='name'><b>Representative Last Name</b></label>" .
        "                  <input type='text' class='form-control' id='lastName' name='lastName' placeholder='Enter representative name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Representative email</b></label>" .
        "                  <input type='email' class='form-control' id='email' name='email' placeholder='Enter email' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Representative number</b></label>" .
        "                  <input type='text' class='form-control' id='number' name='number' placeholder='Enter phone number' required>" .
        "              </div>" .
        "              <input class='btn btn-primary' type='submit' value='Submit'>".
        "           </form>" .
        "       </div>" .
        "  </div>" .
        "</div>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

function showAccBalance() {

    global $accountBalance;

    $html = getBalanceHTML($accountBalance);
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
    global $userCategory;
    $isGold = ($userCategory==='gold') ? "checked" : "";
    $isPrime = ($userCategory==='prime') ? "checked" : "";
    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Employer Category: $userCategory</b></div>" .
        "          <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "               <label class='btn btn-secondary' data-toggle='tooltip' data-placement='top' title='$toolTipEmpPrime'>" .
        "                    <input type='radio' name='options' id='employerPrime' autocomplete='off' $isPrime> Employer Prime" .
        "               </label>" .
        "               <label class='btn btn-warning' data-toggle='tooltip' data-placement='top' title='$toolTipEmpGold'>" .
        "                    <input type='radio' name='options' id='employerGold' autocomplete='off' $isGold> Employer Gold" .
        "               </label>" .
        "          </div> <br>" .
        "     </div>" .
        "</div>".
        "<div class = 'row justify-content-center mt-3'>".
        "    <div class='col-8'>".
        "       <form action='".$_SERVER['PHP_SELF']."?tab=changeAccBalance' method='post'>".
        "          <button type='submit' class='btn-primary' name='downgrade' value='prime'>Downgrade to Prime</button>".
        "          <button type='submit' class='btn-primary' name='upgrade' value='gold'>Upgrade to Gold</button>".
        "       </form>".
        "    </div>".
        "</div>";
    return $html;
}

/**
 * @param string $html
 * @return string
 */
function getMonthlyPaymentRadioButtonsHTML(string $html): string
{
    global $autoPay;
    $paymentMethod = $autoPay ? "Auto" : "Manual";
    $isAuto = $autoPay ? "checked" : "";
    $isManual = $autoPay ? "" : "checked";
    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Payment Method : $paymentMethod</b></div>" .
        "          <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "               <label class='btn btn-info'>" .
        "                    <input type='radio' name='options' id='autoPayMonth' autocomplete='off' $isAuto> Auto Monthly" .
        "               </label>" .
        "               <label class='btn btn-info'>" .
        "                    <input type='radio' name='options' id='manualPayMonth' autocomplete='off' $isManual> Manual Monthly" .
        "               </label>" .
        "          </div>" .
        "     </div>" .
        "</div>" .
        "<div class='row justify-content-center mt-3'>".
        "   <div class='col-8'>".
        "       <form action='".$_SERVER['PHP_SELF']."?tab=changeAccBalance' method='post'>".
        "          <button type='submit' class='btn-primary' name='auto' value='true'>Change to Auto payment</button>".
        "          <button type='submit' class='btn-primary' name='auto' value='false'>Change to Manual payment</button>".
        "       </form>".
        "   </div>".
        "</div>";

    return $html;
}

/**
 * @param float $balance
 * @return string
 */
function getBalanceHTML(float $balance): string
{
    global $monthlyCharge;

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
            "          <form action='".$_SERVER['PHP_SELF']."?tab=makePayment' method='post' onsubmit='return confirmPayment()'>".
            "          <button class='btn btn-success' type='submit' name='amount' value='$monthlyCharge'>Make Payment $$monthlyCharge </button>".
            "          </form>" .
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

function goToPage($url) {
    echo "<script>window.location.href = '$url'</script>";
}

