<?php

include_once('email.php');
include_once('confirmation_code.php');
include_once('student_model.php');

if (isset($_POST['email_id'])) {
    $email = new Email($_POST['email_id']);
    $confirmationCode = new ConfirmationCode();

    $code = $confirmationCode->generateCode();
    if ($code === false) {
        echo "We are facing issue while sending confirmation code. Please contact course Instructor";
        return;
    }

    if ($email->validateEmailAddress() === false) {
        echo "Kindly make sure that you enter your UB Email Address";
        return;
    }

    ob_start();
    $studentModel = new StudentModel();
    if($studentModel->insert($email->getEmailAddress(), $code) === false) {
        $studentModel->update($email->getEmailAddress(), $code);
    }
    ob_end_clean();

    $email->sendMail($code);
} else {
    echo "Student Email Address is not set";
}