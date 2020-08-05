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
$accountStatus = getAccountStatus($username);  // get account status, true(active), false(not active)
$accountBalance = getAccountBalance($username);  // get account balance
$monthlyCharge= getMonthlyCharge($userCategory);
$jobCategories = getJobCategoriesByUsername($username); // get job categories, Technical ...
$_SESSION['jobcategories'] = $jobCategories;  // for cross file data transfer
$paymentInfo = getPaymentInfo();  // payments
$autoPay = getAutoOrManual($username);    // auto payment or maunal payment, true for auto.
$autoPayString = $autoPay ? "auto": "manual";

echo "username: $username &nbsp&nbsp&nbsp&nbsp";
echo "category: $userCategory&nbsp&nbsp&nbsp&nbsp";
echo "autoPayment: $autoPayString&nbsp&nbsp&nbsp&nbsp";
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
                    if (isset($_GET['jobCategory'])) {
                        $jobCategory = $_GET['jobCategory'];
                        $jobsOfCategory = getJobsOfCategoryByUsername($jobCategory);
                        print_r($jobsOfCategory);
                        showPostedJobs($jobsOfCategory);
                    }
                    else {
                        $postedJobsData = getPostedJobsData();
                        showPostedJobs($postedJobsData);
                    }
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



// post requests
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];
    switch ($tab) {
        case "viewJobs":
            $jobID = $_REQUEST['jobID'];
            $op = $_POST['op'];
            echo "operation: $op<br>";
            echo "jobID: $jobID<br>";
            if (changeJobStatus($op, $jobID)) {
                echo "operation success<br><br>";
            } else {
                echo "operation failed<br><br>";
            }
            echo "<br><br><a href='/GUI/employerDash.php?tab=viewJobs'>view jobs</a>";
            break;

        case "postJob":
            $data = array();
            $data["title"] = $_POST['title'];
            $data["category"] = $_POST['category'];
            $data["description"] = $_POST['description'];
            $data["numOpenings"] = $_POST['numOpenings'];
            echo "title: " . $_POST['title'] . "<br>";
            echo "category: " . $_POST['category'] . "<br>";
            echo "description: " . $_POST['description'] . "<br>";
            echo "numOpenings: " . $_POST['numOpenings'] . "<br>";
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
            if (changeApplicationStatus($appName, $jobID, $operation)) {
                echo "operation success";
            } else {
                echo "operation failed";
            }
            echo "<br><br><a href='/GUI/employerDash.php?tab=viewApplications'>view applications</a>";
            break;

        case "changeAccBalance":
            if (isset($_POST['upgrade'])) {
                echo "upgrade to: ". $_POST['upgrade'] . "<br>" ;
                $category = $_POST['upgrade'];
                if (changeUserCategory($category)) echo "operation success<br>";
                else echo "operation failed<br>";
            }
            if (isset($_POST['downgrade'])) {
                echo "downgrade to: ". $_POST['downgrade'] . "<br>" ;
                $category = $_POST['downgrade'];
                if (changeUserCategory($category)) echo "operation success<br>";
                else echo "operation failed<br>";
            }
            if (isset($_POST['auto'])) {
                echo "Change auto payment to auto? : ". $_POST['auto'] . "<br>";
                $isAuto = $_POST['auto'];
                $defaultPayment = getDefaultPayment();
                if (changeAutoManual($defaultPayment, $isAuto)) echo "operation success<br>";
                else echo "operation failed<br>";
            }

            echo "<br><br><a href='/GUI/employerDash.php?tab=viewAccBalance'>view account balance</a>";
            break;

        case "passwordChange":
            $prevPass = $_POST['prevPass'];
            $newPass = $_POST['newPass'];
            echo "previous Password: ". $_POST['prevPass'] . "<br>";
            echo "new Password: ". $_POST['newPass'] . "<br>";
            if (changePassword($prevPass, $newPass)) echo "operation success<br>";
            else echo "operation failed<br>";
            echo "<a href='employerDash.php?tab=viewPasswordChange'>change password page</a>";
            break;

        case "addCreditCard":
            $ccNumber = $_POST['ccNumber'];
            $ccbNumber = $_POST['ccbNumber'];
            $ccExpiration = $_POST['ccExpiration'];
            echo "ccNumber: " .$_POST['ccNumber'] . "<br>";
            echo "ccbNumber: " .$_POST['ccbNumber'] . "<br>";
            echo "ccExpiration: " . $ccExpiration . "<br>";
            if (insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration)) {
                echo "operation success<br>";
            };
            echo "<a href='employerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "addDebitCard":
            $baNumber = $_POST['baNumber'];
            $instituteNumber = $_POST['instituteNumber'];
            $branchNumber = $_POST['branchNumber'];
            echo "baNumber: " .$_POST['baNumber'] . "<br>";
            echo "instituteNumber: " .$_POST['instituteNumber'] . "<br>";
            echo "branchNumber: " .$_POST['branchNumber'] . "<br>";
            if (insertDebitCard($username, $baNumber, $instituteNumber, $branchNumber)) {
                echo "operation success<br>";
            };
            echo "<a href='employerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "changeDebitStatus":
            $op = $_POST['op'];
            $accountNumber = $_REQUEST['accountNumber'];
            echo "account number: " .$_REQUEST['accountNumber'] . "<br>";
            echo "operation: " . $_POST['op'] . "<br>";
            if (changeDebitStatus($username, $op, $accountNumber)) {
                echo "operation success<br>";
            }
            echo "<a href='employerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "changeCreditStatus":
            $op = $_POST['op'];
            $ccNumber = $_REQUEST['ccNumber'];
            $ccExpiry = $_REQUEST['ccExpiry'];
            echo "credit card number: " . $_REQUEST['ccNumber'] . "<br>";
            echo "credit card expiration date: " . $_REQUEST['ccExpiry'] . "<br>";
            echo "operation: " . $_POST['op'] . "<br>";
            if (changeCreditStatus($username, $op, $ccNumber, $ccExpiry)) {
                echo "operation success<br>";
            }
            echo "<a href='employerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "changeContactInfo":
            $employerName = $_POST['eName'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $number = $_POST['number'];
            echo "eName: " .$employerName. "<br>";
            echo "firstName: " .$firstName . "<br>";
            echo "lastName: " .$lastName . "<br>";
            echo "email: " .$email . "<br>";
            echo "number: " .$number . "<br>";
            if (changeContactInfo($username, $employerName, $firstName, $lastName, $email, $number)) {
                echo "operation success. <br>";
            } else {
                echo "operation failed. <br>";
            }
            echo "<a href='employerDash.php'>employer dash homepage</a>";
            break;

        case "makePayment":
            $amount = $_POST['amount'];
            echo "payment Amount: " .$_POST['amount'] ."<br>";
            if (makePayment($amount)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }
            echo "<a href='employerDash.php?tab=viewAccBalance'>view account balance</a>";
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

//  get user's payment method, auto or manual, return true for auto, false for manual.
function getAutoOrManual($username) {
    if (getDefaultPayment()['autoManual'] == 1) return true;
    else return false;
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

    $payments = [$creditCardInfo, $debitCardInfo];

    return [$creditCardInfo, $debitCardInfo];
}

// get default payment info
function getDefaultPayment() {
    global $paymentInfo;
    $creditInfo = $paymentInfo[0];
    $debitInfo = $paymentInfo[1];

    for ($i = 0; $i < count($creditInfo); $i++) {
        if ($creditInfo[$i]['isDefault']) {
            $ccNumber = $creditInfo[$i]['CCNumber'];
            $ccExpiry = $creditInfo[$i]['CCExpiry'];
            $isAuto = $creditInfo[$i]['autoManual'];
            return array("type"=>"credit", "ccNumber"=>$ccNumber, "ccExpiry"=>$ccExpiry, "autoManual"=>$isAuto);
        }
    }
    for ($i = 0; $i < count($debitInfo); $i++) {
        if ($debitInfo[$i]['isDefault']) {
            $accountNumber = $debitInfo[$i]['accountNumber'];
            $isAuto = $debitInfo[$i]['autoManual'];
            return array("type"=>"debit", "accountNumber"=>$accountNumber, "autoManual"=>$isAuto);
        }
    }
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
                "branchNumber"=>$row["BranchNumber"], "isDefault"=>$row["IsDefault"], "autoManual"=>$row["Auto_Manual"]);
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
// get all jobs posted by user of one category
function getJobsOfCategoryByUsername($jobCategory) {
    global $username;
    $data = array();

    $conn = connectDB();
    $sql = "select * from job where EmployerUserName = '$username' and Category = '$jobCategory'";
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


// chang the status of a job (open, close), or delete a job
function changeJobStatus($op, $jobID) {
    $conn = connectDB();
    $sql = "";

    if ($op === "open") {
        $sql = "update job set JobStatus = 1 where JobID = $jobID";
    }
    else if ($op === "close") {
        $sql = "update job set JobStatus = 0 where JobID = $jobID";
    }
    else if ($op === "delete") {
        $sql = "delete from job where JobID = $jobID";
    }

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}


// change application status (review, accepted, denied, sent, hired)
function changeApplicationStatus($appName, $jobID, $operation) {
    $conn = connectDB();
    $sql = "";

    if ($operation === "deny") {
        $sql = "update application set ApplicationStatus = 'denied'
                where ApplicantUserName = '$appName' and JobID = $jobID";
    }
    else if ($operation === "review") {
        $sql = "update application set ApplicationStatus = 'review'
                where ApplicantUserName = '$appName' and JobID = $jobID";
    }
    else if ($operation === "sendOffer") {
        $sql = "update application set ApplicationStatus = 'sent'
                where ApplicantUserName = '$appName' and JobID = $jobID";
    }
    else if ($operation === "hire") {
        $sql = "update application set ApplicationStatus = 'hired'
                where ApplicantUserName = '$appName' and JobID = $jobID";
    }
    else if ($operation === "delete") {
        $sql = "delete from application
                where ApplicantUserName = '$appName' and JobID = $jobID";
    }

    if (mysqli_query($conn, $sql)) {
        return true;
    }
    else {
        return false;
    }
}


// change contact info
function changeContactInfo($username, $employerName, $firstName, $lastName, $email, $number) {
    if (updateEmployerName($username, $employerName) &&
        updateRepresentativeInfo($username, $firstName, $lastName, $email, $number)) {
        return true;
    }
    else { return false; }
}

// change employer's category
function changeUserCategory($category) {
    global $username;
    $conn = connectDB();
    $sql = "update employer set Category = '$category' where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// change auto
function changeAutoManual($defaultPayment, $isAuto) {
    global $username;
    $b = $isAuto==='true' ? 1 : 0;
    $conn = connectDB();
    $sql = "";
    $s = $defaultPayment['type'];
    if ($s === 'credit') {
        $ccNumber = $defaultPayment['ccNumber'];
        $ccExpiry = $defaultPayment['ccExpiry'];
        $sql = "update creditcardinfo set Auto_Manual = $b 
                where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
    }
    else if ($s === 'debit') {
        $accountNumber = $defaultPayment['accountNumber'];
        $sql = "update padinfo set Auto_Manual = $b
                where AccountNumber = '$accountNumber'";
    }
    if (mysqli_query($conn, $sql)) return true;
    return false;
}

// change password
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



// update employer name given username
function updateEmployerName($username, $employerName) {
    $conn = connectDB();
    $sql = "update employer set EmployerName = '$employerName' where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// update representative info given username
function updateRepresentativeInfo($username, $firstName, $lastName, $email, $number) {
    $conn = connectDB();
    $sql = "update user 
            set FirstName = '$firstName', LastName = '$lastName', Email = '$email', ContactNumber = '$number'
            where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// insert a credit card of the username
function insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration) {
    $month = substr($ccExpiration, 0, 2);
    $year = substr($ccExpiration, 2, 4);
    $expDate = $year . "-" . $month . "-1";

    $flag = false;
    $conn = connectDB();
    $sql = "insert into creditcardinfo (CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
            VALUES ($ccNumber, '$expDate', $ccbNumber, 0, 0)";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    }

    if ($flag) {
        if (insertEmployerCC($ccNumber, $username)) return true;
    }
    return false;
}

// insert into employercc
function insertEmployerCC($ccNumber, $username) {
    $conn = connectDB();
    $sql = "insert into employercc (EmployerUserName, CCNumber) values ('$username', '$ccNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// insert debit card info
function insertDebitCard($username, $baNumber, $instituteNumber, $branchNumber) {
    $conn = connectDB();
    $flag = false;
    $sql = "insert into padinfo (AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual) 
            values ('$baNumber', '$instituteNumber', '$branchNumber', 0, 0)";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    }

    if ($flag) {
        if (insertEmployerPad($username, $baNumber)) return true;
    }
    return false;
}

// insert into employerpad table
function insertEmployerPad($username, $baNumber) {
    $conn = connectDB();
    $sql = "insert into employerpad (EmployerUserName, AccountNumber) values ('$username', '$baNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// change debit card status
function changeDebitStatus($username, $op, $accountNumber) {
    if ($op === 'delete') {
        $conn = connectDB();
        $sql = "delete from employerpad where EmployerUserName = '$username' and AccountNumber = '$accountNumber'";
        if (mysqli_query($conn, $sql)) {
            $conn2 = connectDB();
            $sql2 = "delete from padinfo where AccountNumber = '$accountNumber'";
            if (mysqli_query($conn2, $sql2)) return true;
        }
    }
    else if ($op === 'setDefault') {
        setUndefault();
        $conn = connectDB();
        $sql = "update padinfo set IsDefault = 1 where AccountNumber = '$accountNumber'";
        if (mysqli_query($conn, $sql)) return true;
    }
    return false;
}

// change credit card status
function changeCreditStatus($username, $op, $ccNumber, $ccExpiry) {
    if ($op === 'delete') {
        $conn = connectDB();
        $sql = "delete from employercc where EmployerUserName = '$username' and CCNumber = '$ccNumber'";
        if (mysqli_query($conn, $sql)) {
            $conn2 = connectDB();
            $sql2 = "delete from creditcardinfo where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
            if (mysqli_query($conn2, $sql2)) return true;
        }
    }
    else if ($op === 'setDefault') {
        setUndefault();
        $conn = connectDB();
        $sql = "update creditcardinfo set IsDefault = 1 where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
        if (mysqli_query($conn, $sql)) return true;
    }
    return false;
}

// Change current default to undefault
function setUndefault() {
    global $username;
    global $paymentInfo;
    $creditInfo = $paymentInfo[0];
    $debitInfo = $paymentInfo[1];

    for ($i = 0; $i < count($creditInfo); $i++) {
        if ($creditInfo[$i]['isDefault']) {
            $ccNumber = $creditInfo[$i]['CCNumber'];
            $ccExpiry = $creditInfo[$i]['CCExpiry'];
            $conn = connectDB();
            $sql = "update creditcardinfo set IsDefault = 0 where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
            if (!mysqli_query($conn, $sql)) echo "error in setUndefault";
        }
    }
    for ($i = 0; $i < count($debitInfo); $i++) {
        if ($debitInfo[$i]['isDefault']) {
            $accountNumber = $debitInfo[$i]['accountNumber'];
            $conn = connectDB();
            $sql = "update padinfo set IsDefault = 0 where AccountNumber = '$accountNumber'";
            if (!mysqli_query($conn, $sql)) echo "error in setUndefault";
        }
    }
}

// make payment
function makePayment($amount) {
    global $username;
    $conn = connectDB();
    $sql = "update employer set Balance = Balance+$amount where UserName = '$username'";
    if (mysqli_query($conn, $sql)) return true;
    return false;
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
    global $jobCategories;

    $html =
        "<div class='row justify-content-center'>".
        "    <div class = 'col-4'>".
        "    <form action='".$_SERVER['PHP_SELF']."'>" .
        "       <div class='form-group text-center'>" .
        "            <label for='selectCategory'>Select category:</label>" .
        "            <select class='form-control' id='selectCategory' name='jobCategory'>" .
        "                 <option>...</option>";

    for($i = 0; $i < count($jobCategories); $i++) {

        $category = $jobCategories[$i];
        $html .=
        "                 <option value='$category'>$category</option>";
    }
    $html .=
        "            </select>" .
        "      </div>".
        "   <button class='btn btn-primary' type='submit' name='tab' value='viewJobs'>Submit</button>" .
        "   </form>" .
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
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs&jobID=$ID' method='post'>" .
            "       <button type='submit' name='op' value='open' class='btn btn-success'> Open </button>" .
            "    </form>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=viewJobs&jobID=$ID' method='post'>" .
            "       <button type='submit' name='op' value='close' class='btn btn-warning'> Close </button>" .
            "    </form>" .
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
            "     <div class='col-4 text-center my-auto'>" .
            "           <form action='". $_SERVER['PHP_SELF']."?tab=viewApplications&appName=$appName&jobID=$jobID"."' method='post'>".
            "           <button type='submit' name='op' value='deny' class='btn btn-warning'>Deny</button>" .
            "           <button type='submit' name='op' value='review' class='btn btn-secondary'>Review</button>" .
            "           <button type='submit' name='op' value='sendOffer' class='btn btn-primary'>Send Offer</button>" .
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

    if($isDefault == false) {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "   <form action='".$_SERVER['PHP_SELF']."?tab=changeDebitStatus&accountNumber=$accountNumber' method='post'>".
            "     <button type=submit name='op' value='setDefault' class = 'btn btn-primary'>Set Default</button>" .
            "     <button type=submit name='op' value='delete' class = 'btn btn-danger'>Delete</button>" .
            "   </form>".
            "</div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
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

    if ($isDefault == false) {

        $html .=
            "   <div class = 'col-2 text-center'>" .
            "   <form action='".$_SERVER['PHP_SELF']."?tab=changeCreditStatus&ccNumber=$CCNumber&ccExpiry=$CCExpiry' method='post'>".
            "       <button type='submit' name='op' value='setDefault' class = 'btn btn-primary'>Set Default</button>" .
            "       <button type='submit' name='op' value='delete' class = 'btn btn-danger'>Delete</button>" .
            "   </form>".
            "   </div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showContactInfo() {
    $url = $_SERVER['PHP_SELF']."?tab=changeContactInfo";

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
        "       <form action='" . $_SERVER['PHP_SELF'] . "?tab=changeAccBalance' method='post'>" .
        "          <button type='submit' class='btn btn-secondary' name='downgrade' value='prime'>Downgrade to Prime</button>" .
        "          <button type='submit' class='btn btn-warning' name='upgrade' value='gold'>Upgrade to Gold</button>" .
        "       </form>" .
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
    global $autoPay;
    $paymentMethod = $autoPay ? "Auto" : "Manual";
    $isAuto = $autoPay ? "checked" : "";
    $isManual = $autoPay ? "" : "checked";
    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Payment Method : $paymentMethod</b></div>" .
        "       <form action='" . $_SERVER['PHP_SELF'] . "?tab=changeAccBalance' method='post'>" .
        "          <button type='submit' class='btn btn-info' name='auto' value='true'>Change to Auto payment</button>" .
        "          <button type='submit' class='btn btn-info' name='auto' value='false'>Change to Manual payment</button>" .
        "       </form>" .
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
    global $monthlyCharge;
    global $autoPay;

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

    if($balance < 0  || (!$autoPay)) {

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

