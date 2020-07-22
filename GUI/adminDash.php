<?php

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    require_once "../GUI/view/adminDashView.php";

    if(isset($_GET['tab'])) {

        $tab = $_GET['tab'];

        echo "$tab<br>";

        switch ($tab) {     //Make Account Settings navbar visible

            case "viewAccountSettings":
            case "viewContactInfo":
            case "viewPasswordChange":
                echo "<script>document.getElementById('accSettingsNavbar').classList.remove('d-none');</script>";
                break;
        }

        switch ($tab) {
            case "viewJobs":  // view posted jobs
                showPostedJobs();
                break;
            case "viewAllUsers":
                showAllUsers();
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

function showPostedJobs() {
    $html = "";

    for ($i = 0; $i <  5/* count($postedJobsData) */; $i++) {

        //$ID = $postedJobsData[$i]['jobID']

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>"/* . $postedJobsData[$i]['title'] */. "</b></p><br>" .
            "       <p><b>Job ID: </b>" /*. $postedJobsData[$i]['jobID'] */. "</p>" .
            "       <p><b>Date Posted: </b>" /*. $postedJobsData[$i]['datePosted'] */. "</p>" .
            "       <p><b>Category: </b>" /*. $postedJobsData[$i]['category'] */. "</p>" .
            "       <p><b>Description: </b>" /*. $postedJobsData[$i]['description'] */. "</p>" .
            "       <p><b># Openings: </b>" /*. $postedJobsData[$i]['numOfOpenings'] */. "</p>" .
            "       <p><a href=''># Applications: " /*.$postedJobsData[$i]['numOfApplications'] */. "</a></p>" .
            "       <p><b># Hires: </b>" . /*TODO: $postedJobsData[$i]['numOfHires']" */ "To do". "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "         <form action='' method='' onsubmit=''>" .
            "            <button type='submit' name='deleteJobID' value='' class='btn btn-danger'> Delete </button>" .
            "         </form>" .
            "    </div>" .
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"". $html ."\"</script>";
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

function showAllUsers() {

    $html = "";

    for($i = 0; $i < 20 /*TODO: count of users*/; $i++) {

        if($i < 10) {
            $html = viewEmpContInfo($html);
        } else {
            $html = viewSeekerContInfo($html);
        }
    }

    echo "<script>document.getElementById('viewUsers').innerHTML = \"". $html ."\"</script>";
}

function viewEmpContInfo($html /*, $empIDToView*/): string {

    $empName = $empRepName = $empRepEmail = $empRepNumber = "To do";
    $html .=
        "<div class = 'row justify-content-center align-items-center'>" .
        "     <div class = 'col-8 border rounded border-dark'>" .
        "          <p><b>Employer Name: </b> $empName</p>" .
        "          <p><b>Representative Name: </b> $empRepName</p>" .
        "          <p><b>Representative Email: </b> $empRepEmail</p>" .
        "          <p><b>Representative Number: </b>$empRepNumber</p>" .
        "     </div>".
        "     <div class='col-2 d-flex justify-content-center '>" .
        "          <form action='' method='' onsubmit=''>" .
        "               <button type='submit' name='deleteUser' value='' class='btn btn-danger'> Delete </button>" .
        "          </form>" .
        "     </div>" .
        "         <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "              <label class='btn btn-success'>" .
        "                   <input type='radio' name='options' id='activate' autocomplete='off'> Activate" .
        "              </label>" .
        "              <label class='btn btn-warning'>" .
        "                   <input type='radio' name='options' id='deactivate' autocomplete='off'> Deactivate" .
        "              </label>" .
        "         </div>".
        "</div>";

    return $html;
}

function viewSeekerContInfo($html /*, $seekerIDToView*/): string {

    $seekerName = $seekerEmail = $seekerNumber = "To do";
    $html .=
        "<div class = 'row justify-content-center align-items-center'>" .
        "     <div class = 'col-8 border rounded border-dark'>" .
        "          <p><b>Job Seeker Name: </b> $seekerName</p>" .
        "          <p><b>Job Seeker Email: </b> $seekerEmail</p>" .
        "          <p><b>Job Seeker Number: </b>$seekerNumber</p>" .
        "     </div>".
        "    <div class='col-2 d-flex justify-content-center '>" .
        "         <form action='' method='' onsubmit=''>" .
        "            <button type='submit' name='deleteUser' value='' class='btn btn-danger'> Delete </button>" .
        "         </form>" .
        "    </div>" .
        "         <div class='btn-group btn-group-toggle' data-toggle='buttons'>" .
        "              <label class='btn btn-success'>" .
        "                   <input type='radio' name='options' id='activate' autocomplete='off'> Activate" .
        "              </label>" .
        "              <label class='btn btn-warning'>" .
        "                   <input type='radio' name='options' id='deactivate' autocomplete='off'> Deactivate" .
        "              </label>" .
        "         </div>".
    "</div>";

    return $html;
}