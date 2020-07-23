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

<?php
// define variables and set to empty values
$usernameErr = $emailErr = $passwordErr = $accountTypeErr = "";
$paymentErr = $ccNameErr = $ccNumberErr = $ccExpirationErr = "";
$baNumberErr = $transitNumberErr = "";

$username = $email = $password = $accountType = "";
$payment = $ccName = $ccNumber = $ccExpiration = "";
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
    $paymentErr, $ccNameErr, $ccNumberErr, $ccExpirationErr,
    $baNumberErr, $transitNumberErr;

    global $username, $email, $password, $accountType,
    $payment, $ccName, $ccNumber, $ccExpiration,
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
        if (!preg_match("/^[a-zA-Z ]*$/", $username)) {
            $usernameErr = "Only letters and white space allowed";
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
//        echo $_POST["paymentRadio"];
    }

    // validate credit card information
    if ($payment == "creditCard") {
        if (empty($_POST["ccName"])) {
            $ccNameErr = "Credit Card Name should not be empty";
            $validationSuccess = false;
        } else {
            $ccName = test_input($_POST["ccName"]);
            // check if ccname is valid
            if (!preg_match("/^[a-zA-Z ]*$/", $ccName)) {
                $ccNameErr = "Only letters and white space allowed";
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
    echo "create account";
}

?>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <span class="navbar-text">
            <h4>Sign Up for a New Account<h4>
        </span>
    </nav>
    <div class="form">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class = "form-group">
                <label for="email"><p><b>Email</b></p></label>
                <input type="text" class='form-control' placeholder="Enter email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"> <?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <label for="username"><p><b>Username</b></p></label>
                <input type="text" class='form-control' placeholder="Enter username" id="username" name="username"
                       value="<?php echo $username; ?>" required>
                <span class="error"> <?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="password"><p><b>Password</b></p></label>
                <input type="password" class='form-control' placeholder="Enter password" id="password" name="password"
                       value="<?php echo $password; ?>" required>
                <span class="error"> <?php echo $passwordErr; ?></span>
            </div>

            <b>Account type</b>
            <span class="error"> <?php echo $accountTypeErr; ?></span>
            <div onclick="changePrice()" class="account-toolbar btn-group btn-group-toggle" data-toggle='buttons'>
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
            <p><b>How would you like to pay?</b></p>
            <span class="error"> <?php echo $paymentErr; ?></span>
            <div class="row">
                <!-- Credit Card column-->
                <div class="column">
                    <div class="radio">
                        <label><input type="radio" id="creditRadio" name="paymentRadio" value="creditCard"
                                <?php if (isset($payment) && $payment == "creditCard") echo "checked"; ?>>Credit Card</label>
                    </div>

                    <div class="form-group">
                        <label for="ccName"><p><b>Cardholder Name</b></p></label>
                        <input type="text" class='form-control' placeholder="Enter name" id="ccName" name="ccName"
                               value="<?php echo $ccName; ?>">
                        <span class="error"> <?php echo $ccNameErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="ccNumber"><p><b>Credit card number</b></p></label>
                        <input type="text" class='form-control' placeholder="Enter card number" id="ccNumber" name="ccNumber"
                               value="<?php echo $ccNumber; ?>">
                        <span class="error"> <?php echo $ccNumberErr; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="ccExpiration"><p><b>Expiration(MMYYYY)</b></p></label>
                        <input type="text" class='form-control' placeholder="Enter expiration" id="ccExpiration" name="ccExpiration"
                               value="<?php echo $ccExpiration; ?>">
                        <span class="error"> <?php echo $ccExpirationErr; ?></span>
                    </div>

                </div>
                <!-- Bank Account Column -->
                <div class="column">
                    <div class="radio">
                        <label><input type="radio" id="bankAccount" name="paymentRadio" value="bankAccount"
                                <?php if (isset($payment) && $payment == "bankAccount") echo "checked"; ?>>Bank Account</label>
                    </div>
                    <div class="form-group">
                        <label for="baNumber"><p><b>Account number</b></p></label>
                        <input type="text" class='form-control' placeholder="Enter account number" id="baNumber" name="baNumber"
                               value="<?php echo $baNumber; ?>">
                        <span class="error"> <?php echo $baNumberErr; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="transitNumber"><p><b>Transit Number</b></p></label>
                        <input type="text" class='form-control' placeholder="Enter transit number" id="transitNumber" name="transitNumber"
                               value="<?php echo $transitNumber ?>">
                        <span class="error"> <?php echo $transitNumberErr; ?></span>
                    </div>
                </div>
            </div>

            <!--        cancel the input values, and reload page with get request-->
            <input class='btn btn-danger cancel' type='submit' value='Cancel' onclick="location.href=location.href">
            <!--        submit input values, reload page with post request-->
            <input class='btn btn-primary make' type='submit' value='Make Account'>
        </form>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
