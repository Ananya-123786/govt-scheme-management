function welcomeMessage(){
alert("Welcome to Government Scheme Portal");
function validateForm(){
let username = document.getElementById("username").value;

if(username.length < 3){
alert("Username must be at least 3 characters");
return false;
}

return true;
}
}
function showSuccess(){
alert("Registered Successfully");
}
