<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sigh Up</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="css/signup.css" rel="stylesheet">
    <script src="js/scripts.js"></script>
</head>
<body>

/** @noinspection SqlResolve *//** @noinspection SqlResolve */<?php
include_once "db/db_config.php";
// define variables and set to empty values
$usernameErr = $emailErr = $passwordErr = $accountTypeErr = "";
$paymentErr = $ccbNumberErr = $ccNumberErr = $ccExpirationErr = "";
$baNumberErr = $transitNumberErr = "";

$username = $email = $password = $accountType = "";
$payment = $ccbNumber = $ccNumber = $ccExpiration = "";
$baNumber = $transitNumber = "";

$validationSuccess = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST REQUEST<br>";
    $x = validate();
    if ($x) {
        echo "success<br>";
    } else {
        echo "failed<br>";
    }

    /* if validation success, then execute database query to create an account */
    if ($x) {
        createAccount();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo "GET REQUEST";
}


// validation process
function validate() {
    global $usernameErr, $emailErr, $passwordErr, $accountTypeErr,
    $paymentErr, $ccbNumberErr, $ccNumberErr, $ccExpirationErr,
    $baNumberErr, $transitNumberErr;

    global $username, $email, $password, $accountType,
    $payment, $ccbNumber, $ccNumber, $ccExpiration,
    $baNumber, $transitNumber;

    global $validationSuccess;


    // validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $validationSuccess = false;
    } else {
        $email = test_input($_POST["email"]);
        // check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $validationSuccess = false;
        }
    }

    // validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $validationSuccess = false;
    } else {
        $username = test_input($_POST["username"]);
        // check if username is valid
        if (!preg_match("/^[a-zA-Z0-9_ ]*$/", $username)) {
            $usernameErr = "Only letters, digits, '_' and white space allowed";
            $validationSuccess = false;
        }
    }

    // validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $validationSuccess = false;
    } else {
        $password = trim($_POST["password"]);
        if (strlen($password) < 6) {
            $passwordErr = "Password should have at least 6 characters without space in the beginning and ending";
            $validationSuccess = false;
        }
    }

    // validate account type
    if (empty($_POST["accountType"])) {
        $accountTypeErr = "Please choose one account type";
        $validationSuccess = false;
    } else {
//        echo $_POST["accountType"];
    }

    // validate payment method
    if (empty($_POST["paymentRadio"])) {
        $paymentErr = "Please choose one payment method";
        $validationSuccess = false;
    } else {
        $payment = $_POST["paymentRadio"];
    }

    // validate credit card information
    if ($payment == "creditCard") {
        if (empty($_POST["ccbNumber"])) {
            $ccbNumberErr = "Credit Card Name should not be empty";
            $validationSuccess = false;
        } else {
            $ccbNumber = test_input($_POST["ccbNumber"]);
            // check if ccbNumber is valid
            if (!preg_match("/^[0-9]*$/", $ccbNumber)) {
                $ccbNumberErr = "Only digits allowed";
                $validationSuccess = false;
            }
        }

        if (empty($_POST["ccNumber"])) {
            $ccNumberErr = "Credit Card Number should not be emtpy";
            $validationSuccess = false;
        } else {
            $ccNumber = test_input($_POST["ccNumber"]);
            // check if ccNumber is valid
            if (!preg_match("/^[0-9]*$/", $ccNumber)) {
                $ccNumberErr = "Only digits allowed";
                $validationSuccess = false;
            }
        }

        if (empty($_POST["ccExpiration"])) {
            $ccExpirationErr = "Credit Expiration Date should not be empty";
            $validationSuccess = false;
        } else {
            $ccExpiration = test_input($_POST["ccExpiration"]);
            if (!preg_match("/^(0[1-9]|1[0-2])20([2-9][0-9])$/", $ccExpiration)) {
                $ccExpirationErr = "Expiration Date must be formatted as MMYYYY";
                $validationSuccess = false;
            }
        }
    } else if ($payment == "bankAccount") {  // validate bank account information
        if (empty($_POST["baNumber"])) {
            $baNumberErr = "Bank Account Number should not be empty";
            $validationSuccess = false;
        } else {
            $baNumber = test_input($_POST["baNumber"]);
        }

        if (empty($_POST["transitNumber"])) {
            $transitNumberErr = "Transit Number should not be empty";
            $validationSuccess = false;
        } else {
            $transitNumber = test_input($_POST["transitNumber"]);
        }
    }
    return $validationSuccess;
}


function test_input($data) {
    $data = trim($data);  // Strip whitespace (or other characters) from the beginning and end of a string
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// TODO: create an account
function createAccount() {
    echo "create account<br>";
    global $username, $email, $password, $accountType,
           $payment, $ccbNumber, $ccNumber, $ccExpiration,
           $baNumber, $transitNumber;
    $accountType = $_POST["accountType"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $number = $_POST["number"];
    $flag = false;
    $conn = connectDB();
    $sql = "insert into user (UserName, FirstName, LastName, Email, ContactNumber, Password) 
            VALUES ('$username', '$firstName', '$lastName', '$email', '$number', '$password')";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    } else {
        echo "database operation: insert into user table failed<br>";
    }

    if ($flag) {
        if (insertEmployerOrApplicant($accountType, $username)) {
            if (insertPaymentInfo($accountType, $payment, $username)) echo "operation success";
            else echo "database operation: insert into payment information failed";
        } else {
            echo "database operation: insert into employer or applicant table failed<br>";
        }
    }
}

function insertEmployerOrApplicant ($accountType, $username) {
    $conn = connectDB();
    $sql = "";
    if ($accountType === 'employerPrime') {
        $employerName = $_POST["companyName"];
        $sql = "insert into employer (UserName, EmployerName, Activated, Category, Balance)
                values ('$username', '$employerName', 1, 'prime', 0)";
    }
    else if ($accountType === 'employerGold') {
        $employerName = $_POST["companyName"];
        $sql = "insert into employer (UserName, EmployerName, Activated, Category, Balance)
                values ('$username', '$employerName', 1, 'gold', 0)";
    }
    else if ($accountType === 'seekerBasic') {
        $sql = "insert into applicant (UserName, Category, Activated, Balance)
                values ('$username', 'basic', 1, 0)";
    }
    else if ($accountType === 'seekerPrime') {
        $sql = "insert into applicant (UserName, Category, Activated, Balance)
                values ('$username', 'prime', 1, 0)";
    }
    else if ($accountType === 'seekerGold') {
        $sql = "insert into applicant (UserName, Category, Activated, Balance)
                values ('$username', 'gold', 1, 0)";
    }
    if (mysqli_query($conn, $sql)) return true;
    return false;
}


function insertPaymentInfo($accountType, $payment, $username) {
        if ($payment === 'creditCard') {
            $ccNumber = $_POST['ccNumber'];
            $ccbNumber = $_POST['ccbNumber'];
            $ccExpiration = $_POST['ccExpiration'];
            if (insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration)) return true;
        }
        else if ($payment === 'bankAccount') {
            $baNumber = $_POST['baNumber'];
            $transitNumber = $_POST['transitNumber'];
            $branchNumber = $_POST['branchNumber'];
            if (insertDebitCard($username, $baNumber, $transitNumber, $branchNumber)) return true;
        }
        return false;

}


// insert a credit card of the username
function insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration) {
    global $accountType;
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
        if (($accountType === 'employerPrime') || ($accountType === 'employerPrime')) {
            if (insertEmployerCC($ccNumber, $username)) return true;
        } else {
            if (insertApplicantCC($ccNumber, $username)) return true;
        }
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

function insertApplicantCC($ccNumber, $username) {
    $conn = connectDB();
    $sql = "insert into applicantcc (ApplicantUserName, CCNumber) values ('$username', '$ccNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

function insertDebitCard($username, $baNumber, $instituteNumber, $branchNumber) {
    global $accountType;
    $conn = connectDB();
    $flag = false;
    $sql = "insert into padinfo (AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual) 
            values ('$baNumber', '$instituteNumber', '$branchNumber', 0, 0)";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    }

    if ($flag) {
        if (($accountType === 'employerPrime') || ($accountType === 'employerPrime')) {
            if (insertEmployerPad($username, $baNumber)) return true;
        } else {
            if (insertApplicantPad($username, $baNumber)) return true;
        }
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

// insert into employerpad table
function insertApplicantPad($username, $baNumber) {
    $conn = connectDB();
    $sql = "insert into applicantpad (ApplicantUserName, AccountNumber) values ('$username', '$baNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}



?>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <span class="navbar-text">
            <h4>Sign Up for a New Account<h4>
        </span>
    </nav>

    <div class="form">
        <form id="signUpForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="row justify-content-center" style="margin-top: 50px">
                <div class="col-8">
                    <div class='form-group'>
                        <label for='firstName'><b>First Name</b></label>
                        <input type='text' class='form-control' id='firstName' name='firstName' placeholder='Enter first name' required>
                        <span class="error"></span>
                    </div>
                    <div class='form-group'>
                        <label for='lastName'><b>Last Name</b></label>
                        <input type='text' class='form-control' id='lastName' name='lastName' placeholder='Enter last name' required>
                        <span class="error"></span>
                    </div>
                    <div class='form-group'>
                        <label for='number'><b>Number</b></label>
                        <input type='text' class='form-control' id='number' name='number' placeholder='Enter phone number' required>
                        <span class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email"><b>Email</b></label>
                        <input type="text" class='form-control' placeholder="Enter email" id="email" name="email"
                               value="<?php echo $email; ?>" required>
                        <span class="error"> <?php echo $emailErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input type="text" class='form-control' placeholder="Enter username" id="username"
                               name="username"
                               value="<?php echo $username; ?>" required>
                        <span class="error"> <?php echo $usernameErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <input type="password" class='form-control' placeholder="Enter password" id="password"
                               name="password"
                               value="<?php echo $password; ?>" required>
                        <span class="error"> <?php echo $passwordErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirmPass"><b>Re-enter password</b></label>
                        <input type="password" class='form-control' placeholder="Re-enter password" id="confirmPass"
                               name="confirmPass"
                               value="" required>
                        <span class="error"></span>
                    </div>
                    <div class='form-group d-none' id='companyNameField'>
                        <label for='companyName'><b>Company Name</b></label>
                        <input type='text' class='form-control' id='companyName' name='companyName' placeholder='Enter Company Name'>
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center" style="margin-top: 50px">
                <div class="col-8">
                    <b>Account type</b>
                    <span class="error"> <?php echo $accountTypeErr; ?></span>
                    <div onclick="changePrice(); makeCompanyAppear();"
                         class="account-toolbar btn-group btn-group-toggle"
                         data-toggle='buttons'>
                        <label class="btn btn-secondary">
                            <input type="radio" id="employerPrime" name="accountType" value="employerPrime" required>
                            Employer - Prime
                        </label>
                        <label class="btn btn-warning">
                            <input type="radio" id="employerGold" name="accountType" value="employerGold">
                            Employer - Gold
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" id="seekerBasic" name="accountType" value="seekerBasic"> Job
                            Seeker - Basic
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" id="seekerPrime" name="accountType" value="seekerPrime"> Job
                            Seeker - Prime
                        </label>
                        <label class="btn btn-warning">
                            <input type="radio" id="seekerGold" name="accountType" value="seekerGold"> Job
                            Seeker - Gold
                        </label>
                    </div>
                    <p id="cost"><b>Monthly cost $ </b></p>
                </div>
            </div>
            <div class="row justify-content-center" style="margin-top: 50px;">
                <div class="col-8">
                    <p><b>How would you like to pay?</b></p>
                </div>
            </div>
            <span class="error"> <?php echo $paymentErr; ?></span>
            <div class="row justify-content-center">
                <!-- Credit Card column-->
                <div class="col-4">
                    <div class="radio">
                        <label><input type="radio" id="creditRadio" name="paymentRadio" value="creditCard"
                                <?php if (isset($payment) && $payment == "creditCard") echo "checked"; ?>>Credit
                            Card</label>
                    </div>

                    <div class="form-group">
                        <label for="ccbNumber"><b>CCB Number</b></label>
                        <input type="text" class='form-control' placeholder="Enter CCB Number" id="ccbNumber" name="ccbNumber"
                               value="<?php echo $ccbNumber; ?>">
                        <span class="error"> <?php echo $ccbNumberErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="ccNumber"><b>Credit card number</b></label>
                        <input type="text" class='form-control' placeholder="Enter card number" id="ccNumber"
                               name="ccNumber"
                               value="<?php echo $ccNumber; ?>">
                        <span class="error"> <?php echo $ccNumberErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="ccExpiration"><b>Expiration(MMYYYY)</b></label>
                        <input type="text" class='form-control' placeholder="Enter expiration" id="ccExpiration"
                               name="ccExpiration"
                               value="<?php echo $ccExpiration; ?>">
                        <span class="error"> <?php echo $ccExpirationErr; ?></span>
                    </div>

                </div>
                <!-- Bank Account Column -->
                <div class="col-4">
                    <div class="radio">
                        <label><input type="radio" id="bankAccount" name="paymentRadio" value="bankAccount"
                                <?php if (isset($payment) && $payment == "bankAccount") echo "checked"; ?>>Bank Account</label>
                    </div>
                    <div class="form-group">
                        <label for="baNumber"><b>Account number</b></label>
                        <input type="text" class='form-control' placeholder="Enter account number" id="baNumber"
                               name="baNumber"
                               value="<?php echo $baNumber; ?>">
                        <span class="error"> <?php echo $baNumberErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="transitNumber"><b>Institute Number</b></label>
                        <input type="text" class='form-control' placeholder="Enter transit number" id="transitNumber"
                               name="transitNumber"
                               value="<?php echo $transitNumber ?>">
                        <span class="error"> <?php echo $transitNumberErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="branchNumber"><b>Branch Number</b></label>
                        <input type="text" class='form-control' placeholder="Enter transit number" id="branchNumber"
                               name="branchNumber">
                        <span class="error"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center" style="margin-top: 50px;">
                <div class="col-8">
                    <!--        cancel the input values, and reload page with get request-->
                    <input class='btn btn-danger cancel' type='submit' value='Cancel'
                           onclick="location.href=location.href">
                    <!--        submit input values, reload page with post request-->
                    <input class='btn btn-primary make' type='submit' value='Make Account'>
                </div>
            </div>
    </div>
</div>
</form>
</div>
</div>
</div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
