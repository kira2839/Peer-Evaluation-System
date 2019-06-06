<?php

 require "EmailManager.php";

 $sendTo = $_POST["email"];
 $sendTo = htmlentities($sendTo);

 // Construct email body to be sent
 $mail = new Email();
 $subject = "Confirmation code for the evaluation";
 $confirmCode = "CONFIRMED"

 $msg = "Dear Student,\nThank you for initiating evaluation process.\nUse confirmation code: ". $confirmCode . " to
   begin the evaluation.\nP.S. The code will be valid for 15 minutes only.\nRegards,\nCourse Instructor/TA";

 // set message to send with the code
 $htmlMessage = "
   Here is your confirmation code:<br/><br/><b>" . $confirmCode . "</b><br/><br/> Use this code to access
   your evaluation. If it is not used in 15 minutes you will need to request a new one.";

 // call the code to send the message
 try {
   //send mail to student
   mail($sendTo, "$subject", $msg, "From:evaluationsystem@buffalo.edu".'Reply-To: no-reply@buffalo.com')

   // return success confirmation
   echo "Your confirmation code is successfully sent to your email";
   echo "Thank you for contacting us!";
 } catch (Exception $e) {
   // Any other error that is not a user error
   echo "Sorry! Something went wrong. Please try again later.";
 }

?>
