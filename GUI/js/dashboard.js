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

function paymentApplied() {
    alert("Payment Applied");
    location.reload();
}

function editDebitCard(/*TODO: $bankAccountNumber*/) {

    var html =
        "<div class='row justify-content-center'>" +
        "    <div class='col-8'>" +
        "        <form>" +
        "            <div class='form-group'>" +
        "                <label for='baNumber'><b>Account number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter account number' id='baNumber' name='baNumber'" +
        "                       value=''>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='transitNumber'><b>Transit Number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter transit number' id='transitNumber'" +
        "                       name='transitNumber' value=''>" +
        "            </div>" +
        "            <input class='btn btn-primary' type='submit' value='Submit'>" +
        "        </form>" +
        "    </div>" +
        "</div> "

    document.getElementById('accountSettings').innerHTML = html;
}

function editCreditCard(/*, TODO: $CCNumber, CCExpiry*/) {

    var html =
        "<div class='row justify-content-center'>" +
        "    <div class='col-8'>" +
        "        <form>" +
        "            <div class='form-group'>" +
        "                <label for='ccName'><b>Name</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter name' id='ccName' name='ccName' value=''>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='ccNumber'><b>Credit card number</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter card number' id='ccNumber' name='ccNumber'" +
        "                       value=''>" +
        "            </div>" +
        "            <div class='form-group'>" +
        "                <label for='ccExpiration'><b>Expiration(MMYYYY)</b></label>" +
        "                <input type='text' class='form-control' placeholder='Enter expiration' id='ccExpiration'" +
        "                       name='ccExpiration' value=''>" +
        "            </div>" +
        "        </form>" +
        "        <input class='btn btn-primary' type='submit' value='Submit'>" +
        "    </div>" +
        "</div>"

    document.getElementById('accountSettings').innerHTML = html;
}