<?php

include_once('email.php');
include_once('confirmation_code.php');
include_once('utility.php');
include_once('student_model.php');
include_once('session.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');

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

if ($confirmationCode->validateCode($_POST['confirmation_code'], $email->getEmailAddress())) {
    //Mark the conformation code as used to prevent future use
    StudentModel::getInstance()->markConfirmationCodeAsUsed($email->getEmailAddress());

    // We get the session instance
    $sessionObj = Session::getInstance();

    //Populate the session object
    //$evaluation_meaning = EvaluationMeaningModel::getInstance()->getAllData();
    $sessionObj->email_id = $email->getEmailAddress();
    $sessionObj->agent = sha1($_SERVER['HTTP_USER_AGENT']);
    $student_id = StudentModel::getInstance()->getStudentId($email->getEmailAddress());
    $sessionObj->student_id = $student_id;
    $sessionObj->name = StudentModel::getInstance()->getStudentName($student_id);
    $group_id = StudentGroupModel::getInstance()->getGroupID($student_id);
    $sessionObj->group_id = $group_id;
    $group_members_id = StudentGroupModel::getInstance()->getGroupMembersID($group_id);
    $sessionObj->group_members_id = $group_members_id;

    $group_members_names = array();
    foreach ($group_members_id as $val) {
        array_push($group_members_names, StudentModel::getInstance()->getStudentName($val));
    }

    $sessionObj->group_members_names = $group_members_names;
    echo "Validated your details. Soon the evaluation system will be ready for next step. Try Later";
    //Utility::redirectUser("start_evaluation.php");
}