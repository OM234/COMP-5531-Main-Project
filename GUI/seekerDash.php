<?php
session_start();

// first check login status, if not logged in, go to login page
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
    goToPage("/GUI/index.php");
}

/*********** Data models variables ***********************************************************************/

$username = $_SESSION['username'];      // currently logged in user
$accountType = $_SESSION['accountType'];  // current user account type, (job seeker, employer, admin)
$userCategory = getUserCategory($username);  // current user's category, (basic, prime, gold)
$autoPay = getAutoOrManual($username);    // auto payment or maunal payment, true for auto.
$accountStatus = getAccountStatus($username);  // get account status, true(active), false(not active)
$accountBalance = getAccountBalance($username); // get account balance
$monthlyCharge= getMonthlyCharge($userCategory);


echo "username: $username &nbsp&nbsp&nbsp&nbsp";
echo "accountType: $accountType &nbsp&nbsp&nbsp&nbsp";
echo "category: $userCategory&nbsp&nbsp&nbsp&nbsp";
echo "autoPayment: $autoPay&nbsp&nbsp&nbsp&nbsp";
echo "accountStatus: $accountStatus&nbsp&nbsp&nbsp&nbsp";
echo "<br>";
/************** End of data models ************************************************************************/



/*********************** Controllers *********************************************************************/
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    require_once "../GUI/view/seekerDashView.php";

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
            case "viewApplications":
                if ($accountStatus) {
                    $appliedJobsData = getAppliedJobsData($username);
                    showApplications($appliedJobsData);
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "viewContactInfo":
                showContactInfo();
                break;
            case "viewPaymentInfo":
                $paymentInfo = getPaymentInfo();  // get payment info data
                showPaymentInfo();  // show payment info
                break;
            case "viewAccBalance":
                showAccBalance();
                break;
            case "viewPasswordChange":
                showPasswordChange();
                break;
        }
    }

    if(isset($_GET['empContactInfo'])) {

        /* $empIDToView = $_GET['empContactInfo']; */

        viewEmpContInfo(/* $empIDToView */);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];

    switch ($tab) {
        case "applyjob":
            echo "apply job ID: " . $_POST['applyJobID'] . "<br>";
            break;
        case "withdrawapp":
            echo "withdraw application ID: ". $_POST['withdrawAppID'] . "<br>";
            break;
        case "acceptoffer":
            echo "accept offer application ID: ". $_POST['acceptAppID'] . "<br>";
            break;
    }
}



/*********************** End of Controllers ******************************************************/



/************* Data access part *****************************************************************************/
// TODO: get user's category
function getUserCategory($username) {
    return "gold";
}

// TODO: get user's payment method, auto or manual, return true for auto, false for manual.
function getAutoOrManual($username) {
    return true;
}

// TODO: get user's account status, true for active, false for freeze
function getAccountStatus($username) {
    return true;
}

// TODO: get account balance
function getAccountBalance($username) {
    return 50;
}

// TODO: get monthly payment for different user category
function getMonthlyCharge($userCategory) {
    if ($userCategory === 'gold') {
        return 20;
    } else if ($userCategory === 'prime') {
        return 10;
    } else {
        return 0;
    }
}

// TODO: get posted jobs data from database
function getPostedJobsData() {
    $data = array();
    $job1 = array("jobID" =>100, "title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    $job2 = array("jobID" =>200, "title"=>"Job2", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description2", "numOfOpenings"=>2, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    $job3 = array("jobID" =>300, "title"=>"Job3", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description3", "numOfOpenings"=>3, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    $job4 = array("jobID" =>400, "title"=>"Job4", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description4", "numOfOpenings"=>4, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    array_push($data, $job1);
    array_push($data, $job2);
    array_push($data, $job3);
    array_push($data, $job4);
    return $data;
}

// TODO: get applied jobs data from database
function getAppliedJobsData($username) {
    $data = array();
    $job1 = array("jobID" =>100, "title"=>"Job1", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    $job2 = array("jobID" =>200, "title"=>"Job2", "datePosted"=>date("Y-m-d"), "category"=>"category1",
        "description"=>"Description2", "numOfOpenings"=>2, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
    array_push($data, $job1);
    array_push($data, $job2);
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
        "description"=>"Description1", "numOfOpenings"=>1, "numOfApplications"=>3, "numOfHires"=>1, "jobStatus"=>"open");
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
            "       <p class='jobTitle'><b>" . $postedJobsData[$i]['title'] .  "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $postedJobsData[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $postedJobsData[$i]['datePosted'] . "</p>" .
            "       <p><a href='seekerDash.php?empContactInfo=EmpUserName'>Employer: To Do </a></p>" .
            "       <p><b>Category: </b>" . $postedJobsData[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $postedJobsData[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $postedJobsData[$i]['numOfOpenings'] . "</p>" .
            "       <p><b>Job Status: </b>" . $postedJobsData[$i]['jobStatus'] . "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form action='". $_SERVER['PHP_SELF'] ."?tab=applyjob' method='post' onsubmit='return jobApplyAlert()'>" .
            "       <button type='submit' name='applyJobID' value='$ID' class='btn btn-success'> Apply </button>" .
            "    </form>" .
            "    </div>".
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
}

function showApplications($data) {

    $html = "";

    for ($i = 0; $i < 5 /*count(number of applications)*/; $i++) {

        $appID = $data[$i]['appID'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $data[$i]['title'] .  "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $data[$i]['jobID'] . "</p>" .
            "       <p><b>App ID: </b>" . $data[$i]['appID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $data[$i]['datePosted'] . "</p>" .
            "       <p><a href='seekerDash.php?empContactInfo=EmpUserName'>Employer: To Do </a></p>" .
            "       <p><b>Category: </b>" . $data[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $data[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $data[$i]['numOfOpenings'] . "</p>" .
            "       <p><b>Status: </b>" . $data[$i]['josStatus'] . "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex text-center '>" .
            "    <form action='". $_SERVER['PHP_SELF']."?tab=withdrawapp' method='post'>" .
            "       <button type='submit' name='withdrawAppID' value='$appID' class='btn btn-danger'> Withdraw </button>" .
            "    </form>".
            "    <form action='". $_SERVER['PHP_SELF']."?tab=acceptoffer' method='post'>" .
            "       <button type='submit' name='acceptAppID' value='$appID' class='btn btn-success'> Accept Offer </button>" .
            "    </form>" .
            "    </div>".
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
}

function viewEmpContInfo(/* $empIDToView */) {

    $empName = $empRepName = $empRepEmail = $empRepNumber = "To do";
    $html =
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <p><b>Employer Name: </b> $empName</p>" .
        "          <p><b>Representative Name: </b> $empRepName</p>" .
        "          <p><b>Representative Email: </b> $empRepEmail</p>" .
        "          <p><b>Representative Number: </b>$empRepNumber</p>" .
        "     </div>";
        "</div>";

    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
}

function showPaymentInfo() {

    $html =
        "<div class = 'row justify-content-center align-items-center'>".
        "     <div class = 'col-8 text-center'>".
        "          <button class = 'btn btn-success' onclick='editCreditCard(/* null */)'>Add Credit Card</button>" .
        "          <button class = 'btn btn-success' onclick='editDebitCard(/*null*/)'>Add Bank Card</button>" .
        "     </div>".
        "</div>";


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
    $CVVCode = -1; //3-digit code

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
        "     <p><b>CVV: </b>$CVVCode</p>".
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

    /* TODO: populate form */
    $html =
        "<div class = 'row justify-content-center'>" .
        "  <div class = 'col-8'>" .
        "           <form>" .
        "              <div class='form-group'>" .
        "                  <label for='eName'><b>First Name</b></label>" .
        "                  <input type='text' class='form-control' id='firstName' placeholder='Enter first name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='name'><b>Last Name</b></label>" .
        "                  <input type='text' class='form-control' id='lastName' placeholder='Enter last name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Email</b></label>" .
        "                  <input type='email' class='form-control' id='email' placeholder='Enter email' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Number</b></label>" .
        "                  <input type='text' class='form-control' id='number' placeholder='Enter phone number' required>" .
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

    if(/*TODO: not a basic seeker */ false) {
        $html = getMonthlyPaymentRadioButtonsHTML($html);
    }
    $html = getSeekerCategoryHTML($html);

    echo "<script>document.getElementById('accountSettings').innerHTML = \"". $html ."\"</script>";
}

/**
 * @param string $html
 * @return string
 */
function getSeekerCategoryHTML(string $html): string
{
    $toolTipSeekerBasic = "You can only view jobs but cannot apply. No charge";
    $toolTipSeekerPrime = "You can view jobs as well as apply for up to five jobs. A monthly charge of $10 will be applied. ";
    $toolTipSeekerGold = "You can view and apply to as many jobs as you want. A monthly charge of $20 will be applied.";

    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Job Seeker Category</b></div>" .
        "          <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "               <label class='btn btn-secondary' data-toggle='tooltip' data-placement='top' title='$toolTipSeekerBasic'>" .
        "                    <input type='radio' name='options' id='seekerBasic' autocomplete='off'> Job Seeker Basic" .
        "               </label>" .
        "               <label class='btn btn-success' data-toggle='tooltip' data-placement='top' title='$toolTipSeekerPrime'>" .
        "                    <input type='radio' name='options' id='seekerPrime' autocomplete='off'> Job Seeker Prime" .
        "               </label>" .
        "               <label class='btn btn-warning' data-toggle='tooltip' data-placement='top' title='$toolTipSeekerGold'>" .
        "                    <input type='radio' name='options' id='seekerGold' autocomplete='off'> Job Seeker Gold" .
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