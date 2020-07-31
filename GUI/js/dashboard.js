function viewJobsEmployer() {

    document.getElementById("postJob").style.visibility = "hidden";

    var html = "";

    for (i = 0; i < 5; i++) {   //TODO: number of posted jobs

        var datePosted = "";
        var category = "";
        var description = "";
        var openings = "";
        var applications = "";

        html += "    <div class = \"row justify-content-center\">\n" +
            "        <div class = \"col-10 border\">\n" +
            "            <p class=\"jobTitle\"><b>Job Title goes here</b></p> <br>\n" +
            "            <p><b>Date Posted: </b>" + datePosted + "</p>\n" +
            "            <p><b>Category: </b>" + category + "</p>\n" +
            "            <p><b>Description: </b>" + description + "</p>\n" +
            "            <p><b># Openings: </b>" + openings + "</p>\n" +
            "            <p><b># Applications: </b>" + applications + "</p>\n" +
            "        </div>\n" +
            "    </div>";
    }
    document.getElementById("viewJobs").innerHTML = html;
}

function viewJobs() {

    var html = "";

    for (i = 0; i < 5; i++) {   //TODO: number of posted jobs

        var datePosted = "";
        var category = "";
        var description = "";
        var openings = "";
        var applications = "";

        html += "    <div class = \"row justify-content-center\">\n" +
            "        <div class = \"col-10 border\">\n" +
            "            <p class=\"jobTitle\"><b>Job Title goes here</b></p> <br>\n" +
            "            <p><b>Date Posted: </b>" + datePosted + "</p>\n" +
            "            <p><b>Category: </b>" + category + "</p>\n" +
            "            <p><b>Description: </b>" + description + "</p>\n" +
            "            <p><b># Openings: </b>" + openings + "</p>\n" +
            "            <p><b># Applications: </b>" + applications + "</p>\n" +
            "        </div>\n" +
            "    </div>";
    }
    document.getElementById("viewJobs").innerHTML = html;
}

function postJob() {

    document.getElementById("viewJobs").innerHTML = "";
    document.getElementById("postJob").style.visibility = "visible";
}

function deleteJob(ID) {
    return confirm("Are you sure you want to delete job with ID #" + ID);
}

function viewApplications(ID) {
    alert("seeint me");
}

// validate post job form
function validateForm() {
    let title = document.forms["postJobForm"]["title"];
    if (title.length > 50) {
        document.getElementById("titleError").innerHTML = "Title length must less than 50 characters";
        return false;
    }

    let numberOpenings = document.getElementById("numOpenings").value;
    let text = "";
    // if numberOpenings Not a Number or less than 1
    if (isNaN(numberOpenings) || numberOpenings < 1) {
        text = "Input not valid";
        document.getElementById("error").innerHTML = text;
        return false;
    }
}

function confirmPayment() {
    return confirm("Are you sure to make payment?");
}

function editDebitCard(/*TODO: $bankAccountNumber*/) {

    var html =
        "<div class='row justify-content-center'>" +
        "    <div class='col-8'>" +
        "        <form action='/GUI/employerDash.php?tab=addDebitCard' method='post'>" +
        "            <div class='form-group'>" +
        "                <label for='baNumber'><b>Account number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter account number' id='baNumber' name='baNumber'" +
        "                       value=''>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='instituteNumber'><b>Institute Number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter institute number' id='instituteNumber'" +
        "                       name='instituteNumber' value=''>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='branchNumber'><b>Branch Number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter branch number' id='branchNumber'" +
        "                       name='branchNumber' value=''>" +
        "            </div>" +
        "            <input class='btn btn-primary' type='submit' value='Submit'>" +
        "        </form>" +
        "    </div>" +
        "</div> "

    document.getElementById('accountSettings').innerHTML = html;
}

function editCreditCard() {
    var html =
        "<div class='row justify-content-center'>" +
        "    <div class='col-8'>" +
        "        <form action='/GUI/employerDash.php?tab=addCreditCard' method='post'>" +
        "            <div class='form-group'>" +
        "                <label for='ccNumber'><b>Credit card number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter card number' id='ccNumber' name='ccNumber'" +
        "                       value='' required>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='ccbNumber'><b>CCB Number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter CVV' id='ccbNumber' name='ccbNumber'" +
        "                       value='' required>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='ccExpiration'><b>Expiration(MMYYYY)</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter expiration' id='ccExpiration'" +
        "                       name='ccExpiration' value='' required>" +
        "            </div>" +
        "        <input class='btn btn-primary' type='submit' value='Submit'>" +
        "        </form>" +
        "    </div>" +
        "</div>"

    document.getElementById('accountSettings').innerHTML = html;
}

function jobApplyAlert() {

    alert("You have applied for job");
    return true;
}

function withdrawConfirm() {
    return confirm("Are you sure you want to withdraw?");
}

function acceptOfferAlert() {
    alert ("Offer accepted, awaiting final hire decision");
    return true;
}

function confirmPassword() {
    let prevPass = document.getElementById('prevPass').value;
    let newPass = document.getElementById('newPass').value;
    let conNewPass = document.getElementById('conNewPass').value;
    console.log(prevPass);
    console.log(newPass);
    console.log(conNewPass);
    if (prevPass === newPass) {
        alert("new password is the same with previous password!");
        return false;
    }
    return newPass === conNewPass;
    // return false;

}
function addJobCategory() {
    document.getElementById("newCategoryTextArea").classList.remove("d-none");
    document.getElementById("category").classList.add("d-none");
    document.getElementById("catLabel").classList.add("d-none");
}