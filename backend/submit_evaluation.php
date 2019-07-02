<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('evaluation_handle.php');
include_once('email.php');

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

if (!isset($sessionObj->course_name)) {
    echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Course not selected</p>
	</div>
EOC;
    return;
}

if (isset($_POST['form_data'])) {
    $form_data = $_POST['form_data'];
    if (EvaluationHandle::getInstance()->insertIntoTable($form_data)) {
        echo <<<EOC
    <div id="display_evaluation_submit_result" class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p id="success_evaluation_message"><span class="ui-icon ui-icon-circle-check" style="float: left; margin-right: .3em;"></span>
		Successfully submitted your evaluation for $sessionObj->course_name. A confirmation email has been sent</p>
	</div> <br> <br>
EOC;
        $sessionObj->evaluation = EvaluationHandle::getInstance()->displayEvaluation($sessionObj);
        $email = new Email($sessionObj->email_id);
        $email->sendAckMailOnEvaluation($sessionObj->evaluation);
    } else {
        echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Failed to submit your evaluation. Please try again</p>
	</div>
EOC;
    }
    return "success";
}
