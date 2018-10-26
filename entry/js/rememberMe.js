/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Cookie script - Scott Andrew
Popup script, Copyright 2005, Sandeep Gangadharan */

function newCookie(name,value,days) {
 var days = 10;   // the number at the left reflects the number of days for the cookie to last
                 // modify it according to your needs
 if (days) {
   var date = new Date();
   date.setTime(date.getTime()+(days*24*60*60*1000));
   var expires = "; expires="+date.toGMTString(); }
   else var expires = "";
   document.cookie = name+"="+value+expires+"; path=/"; }

function readCookie(name) {
   var nameSG = name + "=";
   var nuller = '';
  if (document.cookie.indexOf(nameSG) == -1)
    return nuller;

   var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
  if (c.indexOf(nameSG) == 0) return c.substring(nameSG.length,c.length); }
    return null; }

function eraseCookie(name) {
  newCookie(name,"",1); }

function toMem(a) {
    newCookie('theNet_name', document.form.net_name.value);     // add a new cookie as shown at left for every
    newCookie('thePassword', document.form.password.value);   // field you wish to have the script remember
}

function remCookie() {
document.form.net_name.value = readCookie("theNet_name");
document.form.password.value = readCookie("thePassword");
}

// Multiple onload function created by: Simon Willison
// http://simon.incutio.com/archive/2004/05/26/addLoadEvent
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  remCookie();
});
