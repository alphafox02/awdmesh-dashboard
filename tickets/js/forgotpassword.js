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

function forgot() {
// Optional: Show a waiting message in the layer with ID ajax_response
document.getElementById('login_response').innerHTML = '<p class="error">Checking...</p>';
// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
var email = encodeURI(document.getElementById('emailaddress').value);
// Set te random number to add to URL request
nocache = Math.random();
// Pass the login variables like URL variable
http.open('get', 'forgot.php?email='+email+'&nocache = '+nocache);
http.onreadystatechange = loginReply;
http.send(null);
}
function loginReply() {
if(http.readyState == 4){
var response = http.responseText;

if(parseInt(response) == -1) {
// if login fails
document.getElementById('login_response').innerHTML = '<p class="error">Unable to find user account.</p>';
// else if login is ok show a message: "Welcome + the user name".
document.getElementById('emailaddress').value="";
document.getElementById('emailaddress').focus() ;

} 

if(parseInt(response) == 1) {
document.getElementById('login_response').innerHTML = '<p class="tip">Login details sent.</p>';
setTimeout('go_to_private_page()', 2000);
}
}
}

function go_to_private_page()
{
window.location = 'login.html'; // Members Area
}

