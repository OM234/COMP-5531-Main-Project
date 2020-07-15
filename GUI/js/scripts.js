function passPrompt() {
	var email = prompt("Enter email", "");
    if(/*TODO: email exists*/false) {
    	/*sent email*/
    } else {
       alert("Email does not exist");
    } 
}

function newAccount() {
	
	var username = prompt("Enter username");
	var password;
	if(/*TODO: username already exists*/ false) {
		alert("username already exists");
		return;
	} else {
		password = prompt("enter password")
	}
	
}