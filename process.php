<?php
//if "email" variable is filled out, send email
  if (isset($_REQUEST['email']))  {

  //Email information
  $email = $_REQUEST['email'];
  // $subject = $_REQUEST['subject'];
  $subject = "Your confirmation code for the online feedback system";
  // $comment = $_REQUEST['comment'];

  //send email
  mail($email, "$subject", "From:kuduvago@buffalo.edu");

  //Email response
  echo "Thank you for contacting us!";
  }
  else  {
	  echo "HTML Attribute 'Required' is not set in the HTML input form.";
	   }
?>