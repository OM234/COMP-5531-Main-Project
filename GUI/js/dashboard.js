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