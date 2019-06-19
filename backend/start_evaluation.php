<?php

require('email.php');
require('confirmation_code.php');

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

$confirmationCode->validateCode($_POST['confirmation_code'], $email->getEmailAddress());
