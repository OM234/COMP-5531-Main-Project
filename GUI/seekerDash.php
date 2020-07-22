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
//            case "postJob":  // post a job
//                showPostJobForm();
//                break;
//            case "viewApplications":
//                showApplications();
//                break;
//            case "viewContactInfo":
//                showContactInfo();
//                break;
//            case "viewPaymentInfo":
//                showPaymentInfo();
//                break;
//            case "viewAccBalance":
//                showAccBalance();
//                break;
//            case "viewPasswordChange":
//                showPasswordChange();
//                break;

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
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form>" .
            "       <button type='Apply' onclick = 'jobApplyAlert()' name='' value='' class='btn btn-success'> Apply </button>" .
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