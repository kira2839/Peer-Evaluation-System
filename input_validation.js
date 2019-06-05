function isValid(){
var emailID = document.getElementById('emailID').value;
if (emailID.includes("@")==false ||emailID.includes(".")==false ){
alert("Please Enter a Valid emailID");
mouseOut();}
else{
if (emailID.includes("@buffalo.edu")==true){
	mouseOver();
}
else{
	alert("Kindly Enter a UB email ID ending with @buffalo.edu");
	mouseOut();
}
}
}
function mouseOver() {
  document.getElementById("submit_button").style.backgroundColor = "green";
}

function mouseOut() {
  document.getElementById("submit_button").style.backgroundColor = "";
}

