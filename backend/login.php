<?php

include_once('email.php');
include_once('confirmation_code.php');
include_once('utility.php');
include_once('student_model.php');
include_once('session.php');

if (!isset($_POST['email_id_tab2'])) {
    echo "Student Email Address is not set";
}

if (!isset($_POST['confirmation_code'])) {
    echo "Confirmation code is not set";
}

$email = new Email($_POST['email_id_tab2']);
$confirmationCode = new ConfirmationCode();

if ($email->validateEmailAddress() === false) {
    echo "Invalid Email Address/Confirmation code";
    return;
}

if($confirmationCode->validateCode($_POST['confirmation_code'], $email->getEmailAddress())) {
    //Mark the conformation code as used to prevent future use
    StudentModel::getInstance()->markConfirmationCodeAsUsed($email->getEmailAddress());

    // We get the session instance
    $sessionObj = Session::getInstance();
    $sessionObj->email_id = $email->getEmailAddress();

    // Store the HTTP_USER_AGENT:
    $sessionObj->Agent = sha1($_SERVER['HTTP_USER_AGENT']);
    Utility::redirectUser("start_evaluation.php");
}