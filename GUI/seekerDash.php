<?php

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
            case "viewJobs":  // view posted jobs
                showPostedJobs();
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

    if(isset($_GET['empContactInfo'])) {

        /* $empIDToView = $_GET['empContactInfo']; */

        viewEmpContInfo(/* $empIDToView */);
    }
}

function showPostedJobs() {

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

    for ($i = 0; $i < 5 /*count($postedJobsData which seeker not applied to)*/; $i++) {

        /*$ID = $postedJobsData[$i]['jobID'];*/

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" /*. $postedJobsData[$i]['title'] */.  "</b></p><br>" .
            "       <p><b>Job ID: </b>" /*. $postedJobsData[$i]['jobID'] */. "</p>" .
            "       <p><b>Date Posted: </b>" /*. $postedJobsData[$i]['datePosted'] */. "</p>" .
            "       <p><a href='seekerDash.php?empContactInfo=EmpUserName'>Employer: To Do </a></p>" .
            "       <p><b>Category: </b>" /*. $postedJobsData[$i]['category'] */. "</p>" .
            "       <p><b>Description: </b>" /*. $postedJobsData[$i]['description'] */. "</p>" .
            "       <p><b># Openings: </b>" /*. $postedJobsData[$i]['numOfOpenings'] */. "</p>" .
            "       <p><b>Job Status: </b>" . /*TODO: openOrClosed" */ "To do: open or closed". "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form>" .
            "       <button type='' onclick = 'jobApplyAlert()' name='' value='' class='btn btn-success'> Apply </button>" .
            "    </form>" .
            "    </div>".
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
}

function showApplications() {

    $html = "";

    for ($i = 0; $i < 5 /*count(number of applications)*/; $i++) {

        /*$ID = $postedJobsData[$i]['jobID'];*/

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" /*. $postedJobsData[$i]['title'] */.  "</b></p><br>" .
            "       <p><b>Job ID: </b>" /*. $postedJobsData[$i]['jobID'] */. "</p>" .
            "       <p><b>Date Posted: </b>" /*. $postedJobsData[$i]['datePosted'] */. "</p>" .
            "       <p><a href='seekerDash.php?empContactInfo=EmpUserName'>Employer: To Do </a></p>" .
            "       <p><b>Category: </b>" /*. $postedJobsData[$i]['category'] */. "</p>" .
            "       <p><b>Description: </b>" /*. $postedJobsData[$i]['description'] */. "</p>" .
            "       <p><b># Openings: </b>" /*. $postedJobsData[$i]['numOfOpenings'] */. "</p>" .
            "       <p><b>Status: </b>" /*. denied, under review, offer sent, offer accepted, hired*/. "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex text-center '>" .
            "    <form>" .
            "       <button onclick = 'withdrawConfirm()' name='' value='' class='btn btn-danger'> Withdraw </button>" .
            "       <button onclick = 'acceptOfferAlert()' name='' value='' class='btn btn-success'> Accept Offer </button>" .
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
        "                  <input type='text' class='form-control' id='firstName' placeholder='Enter first name'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='name'><b>Last Name</b></label>" .
        "                  <input type='text' class='form-control' id='lastName' placeholder='Enter last name'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Email</b></label>" .
        "                  <input type='email' class='form-control' id='email' placeholder='Enter email'>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Number</b></label>" .
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