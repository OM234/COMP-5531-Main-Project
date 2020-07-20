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

    confirm("Are you sure you want to delete job with ID #" + ID);
    //TODO: return confirm value
}

function viewApplications(ID) {
    alert("seeint me");
}
