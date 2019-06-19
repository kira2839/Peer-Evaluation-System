<?php

require('email.php');
require('confirmation_code.php');

if (isset($_POST['email_id'])) {
    $email = new Email($_POST['email_id']);
    $confirmationCode = new ConfirmationCode();

    $code = $confirmationCode->generateCode($_POST['email_id']);
    if ($code === false) {
        echo "We are facing issue while sending confirmation code. Please contact course Instructor";
        return;
    }

    if ($email->validateEmailAddress() === false) {
        echo "Kindly make sure that you enter your UB Email Address";
        return;
    }

    $email->sendMail($code);
} else {
    echo "Student Email Address is not set";
}