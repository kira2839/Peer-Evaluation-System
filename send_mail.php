<?php
  //if "email-id" available send email
  if (isset($_REQUEST['email-id']))  {

    $email = $_REQUEST['email-id'];
    $subject = "Confirmation code for the evaluation";

    //Construct email body to be sent
    $confirmationCode = "CONFIRMED";
    $msg = "Dear Student,\r\n\r\nThank you for initiating evaluation process.\r\n".
         "Use confirmation code: ". $confirmationCode .
         " to begin the evaluation.\r\nP.S. ".
         "The code will be valid for 15 minutes only.\r\n\r\nRegards,\r\nInstructor/TA";

    //send message with code to student
    mail($email, "$subject", $msg, "From:evaluationsystem@buffalo.edu\r\nReply-To: no-reply@buffalo.com");

    print "Thank you for contacting us!\n";
    print "Your confirmation code is successfully sent to your email: ".$email;
  }
  else  {
    echo "HTML Attribute 'Required' is not set in the HTML input form.";
  }
?>
