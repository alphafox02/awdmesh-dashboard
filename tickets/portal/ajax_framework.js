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

function activatevoucher() {
// Optional: Show a waiting message in the layer with ID ajax_response
// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
var voucher = encodeURI(document.getElementById('vouchercode').value);
// Set te random number to add to URL request
nocache = Math.random();
// Pass the login variables like URL variable
//SetCookie('awdaccess',voucher,365);
http.open('get', 'activatevoucher.php?voucher='+voucher+'&nocache = '+nocache);
http.onreadystatechange = loginReply;
http.send(null);
}

function voucherlogin() {
setTimeout('go_to_redir_page()', 0);
}

function SetCookie(cookieName,cookieValue,nDays) {
 var today = new Date();
 var expire = new Date();
 if (nDays==null || nDays==0) nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}


function loginReply() {
if(http.readyState == 4){
var response = http.responseText;
if(parseInt(response) == -1){
setTimeout('go_to_private_page()', 0);
} else {
alert(response);
}
}
}

function go_to_private_page(voucherid)
{
var voucher = encodeURI(document.getElementById('vouchercode').value);
window.location = 'redirect.php?voucherid='+voucher; // Members Area
}

function go_to_redir_page()
{
window.location = 'redirect.php";
}

