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


function edittheform() {

var sredirect=encodeURIComponent(document.getElementById("siteredirect17").value)
var spassword=encodeURIComponent(document.getElementById("sitepassword17").value)
var nrefs=encodeURIComponent(document.getElementById("nref17").value)
var parameters="siteredirect17="+namevalue+"&sitepassword17="+agevalue+"&nref="+nrefs
http.open("POST", "editform.php", true)
http.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
http.send(parameters)

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
setTimeout('go_to_private_page()', 1500);
}
}
}

function go_to_private_page()
{
window.location = 'dashboard.php'; // Members Area
}

