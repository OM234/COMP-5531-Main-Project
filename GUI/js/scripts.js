function passPrompt() {

    var email = prompt("Enter email", "");
    if (/*TODO: email exists*/false) {
        /*sent email*/
    } else {
        alert("Email does not exist");
    }
}

function newAccount() {

    var username = prompt("Enter username");
    var password;
    if (/*TODO: username already exists*/ false) {
        alert("username already exists");
        return;
    } else {
        password = prompt("enter password")
    }

}

function login() {

    var categories = document.getElementsByName('radioSelect');
    var category;

    for (i = 0; i < categories.length; i++) {
        if (categories[i].checked) {
            category = categories[i];
            break;
        }
    }

    switch (category.value) {

        case 'employer':
            window.location.href = 'employerDash.html';
            break;
        case 'jobSeeker':
            window.location.href = 'seekerDash.html';
            break;
        case 'administrator':
            window.location.href = 'adminDash.html';
            break;
    }
}

function changePrice() {

    var categories = document.getElementsByName('radioSelect');
    var category;

    for (i = 0; i < categories.length; i++) {
        if (categories[i].checked) {
            category = categories[i];
            break;
        }
    }

    switch (category.value) {
        case 'employerPrime':
            document.getElementById("cost").innerHTML = "<b>Monthly cost $50</b>";
            break;
        case 'employerGold':
            document.getElementById("cost").innerHTML = "<b>Monthly cost $100</b>";
            break;
        case 'seekerBasic':
            document.getElementById("cost").innerHTML = "<b>Monthly cost $0</b>";
            break;
        case 'seekerPrime':
            document.getElementById("cost").innerHTML = "<b>Monthly cost $10</b>";
            break;
        case 'seekerGold':
            document.getElementById("cost").innerHTML = "<b>Monthly cost $20</b>";
            break;
    }
}