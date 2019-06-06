function isValid(){
var email_id = document.getElementById('email_id').value;


if(email_id.endsWith("@buffalo.edu")){
if(email_id.match(".").length<2&&email_id.match("@").length<2&&email_id.split("@")[0].match("^[A-z0-9]+$")){
	if(document.getElementById('submit_button').disabled){
	document.getElementById('submit_button').disabled=false;}
	mouseOver();
}
else{
	alert("Your Email ID can contain either letters and numbers or letters with optional '.'");
	if(!document.getElementById('submit_button').disabled){
	document.getElementById('submit_button').disabled=true;}
	mouseOut();
	
}
}
else
{
	alert("Kindly make sure that you enter your UB Email ID");
	if(!document.getElementById('submit_button').disabled){
	document.getElementById('submit_button').disabled=true;}
	mouseOut();
	
}
}

function mouseOver() {
  document.getElementById("submit_button").style.backgroundColor = "green";
}

function mouseOut() {
  document.getElementById("submit_button").style.backgroundColor = "";
}

function onSubmit(){
// document.getElementById('email_id').value = document.getElementById('email_id').value + "@buffalo.edu";
}
