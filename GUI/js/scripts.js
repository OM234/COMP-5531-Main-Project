﻿function passPrompt() {

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

    for (let i = 0; i < categories.length; i++) {
        if (categories[i].checked) {
            category = categories[i];
            break;
        }
    }
    if (category === undefined) {
        alert("choose one category");
        return false;
    }

    var input_password = document.getElementById("input-password");
    var md5_password = document.getElementById("md5-password");
    // transform input password into md5 password
    md5_password.value = MD5(input_password.value);

    return true;

}

function changePrice() {

    var categories = document.getElementsByName('accountType');
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

function makeCompanyAppear(){

    var categories = document.getElementsByName('accountType');
    var category;

    for (i = 0; i < categories.length; i++) {
        if (categories[i].checked) {
            category = categories[i];
            break;
        }
    }

    switch (category.value) {
        case 'employerPrime':
        case 'employerGold':
            document.getElementById('companyNameField').classList.remove('d-none');
            break;
        case 'seekerBasic':
        case 'seekerPrime':
        case 'seekerGold':
            document.getElementById('companyNameField').classList.add('d-none');
            break;
    }
}