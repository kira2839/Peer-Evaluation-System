<?php

include_once('email.php');
include_once('confirmation_code.php');
include_once('website_page_handle.php');
include_once('session.php');

include_once('student_model.php');
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

if ($email->isStudentPartOfEvaluationSystem() === false) {
    echo "Invalid Email Address/Confirmation code";
    return;
}

if ($email->validateEmailAddress() === false) {
    echo "Invalid Email Address/Confirmation code";
    return;
}

if ($confirmationCode->validateCode($_POST['confirmation_code'], $email->getEmailAddress())) {
    //Mark the conformation code as used to prevent future use
    StudentModel::getInstance()->markConfirmationCodeAsUsed($email->getEmailAddress());

    // We get the session instance
    $sessionObj = Session::getInstance();

    // session started more than 30 minutes ago
    //session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    //$_SESSION['CREATED'] = time();  // update creation time

    //Populate the session object
    //1. Student Email Id
    $sessionObj->email_id = $email->getEmailAddress();
    $sessionObj->agent = sha1($_SERVER['HTTP_USER_AGENT']);
    $sessionObj->created_time = time();

    //2. Student Id
    $student_id = StudentModel::getInstance()->getStudentId($email->getEmailAddress());
    $sessionObj->student_id = $student_id;

    //3. Student Name
    $sessionObj->name = StudentModel::getInstance()->getStudentName($student_id);

    //4. Student Group ID
    $group_id = StudentGroupModel::getInstance()->getGroupID($student_id);
    $sessionObj->group_id = $group_id;

    //5. Student All Group Members IDs
    $group_members_id = StudentGroupModel::getInstance()->getGroupMembersID($group_id);
    $sessionObj->group_members_id = $group_members_id;

    //6. Evaluation Meaning
    $sessionObj->evaluation_meaning = EvaluationMeaningModel::getInstance()->getAllData();

    //7. Evaluation Meaning
    $group_members_names = array();
    foreach ($group_members_id as $val) {
        array_push($group_members_names, StudentModel::getInstance()->getStudentName($val));
    }
    $sessionObj->group_members_names = $group_members_names;
    echo "Validated your details. Soon the evaluation system will be ready for next step. Try Later";
    //WebSitePageHandle::redirectUser("start_evaluation.php");
}