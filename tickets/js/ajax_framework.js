/* ---------------------------- */
/* XMLHTTPRequest Enable */
/* ---------------------------- */
function createObject() {
var request_type;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
request_type = new ActiveXObject("Microsoft.XMLHTTP");
}else{
request_type = new XMLHttpRequest();
}
return request_type;
}

var http = createObject();

/* -------------------------- */
/* LOGIN */
/* -------------------------- */
/* Required: var nocache is a random number to add to request. This value solve an Internet Explorer cache issue */
var nocache = 0;

function login() {
// Optional: Show a waiting message in the layer with ID ajax_response
// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
var email = encodeURI(document.getElementById('emailLogin').value);
var psw = encodeURI(document.getElementById('pswLogin').value);
// Set te random number to add to URL request
nocache = Math.random();
// Pass the login variables like URL variable
http.open('get', 'login.php?email='+email+'&psw='+psw+'&nocache = '+nocache);
http.onreadystatechange = loginReply;
http.send(null);
}
function loginReply() {
if(http.readyState == 4){
var response = http.responseText;
if(parseInt(response) == -1){
// if login fails
document.getElementById('login_response').innerHTML = '<p class="error">Login failed! Verify user and password</p>';
// else if login is ok show a message: "Welcome + the user name".
document.getElementById('emailLogin').value="";
document.getElementById('pswLogin').value="";
document.getElementById('emailLogin').focus() ;
} else {
document.getElementById('login_response').innerHTML = '<p class="tip">Logged in. Please wait</p>';
document.getElementById('emailLogin').disabled=true;
document.getElementById('pswLogin').disabled=true;
setTimeout('go_to_private_page()', 1500);
}
}
}

function go_to_private_page()
{
window.location = 'dashboard.php'; // Members Area
}

