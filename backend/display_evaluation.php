<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('evaluation_handle.php');
include_once('email.php');

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

//Start creating the html tags
WebSitePageHandle::includeSiteHeader();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

if (!isset($sessionObj->course_name)) {
    echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Course is not selected</p>
	</div>
EOC;
    return;
}

if(isset($sessionObj->evaluation) AND $sessionObj->evaluation !== NULL) {
    WebSitePageHandle::startBodyTag();
    echo <<<EOC1
    <p class="show_msg"> Evaluation for $sessionObj->course_name </p>
EOC1;
    EvaluationHandle::getInstance()->displayButtons();

    echo <<<EOC2
    <br><form id="display_evaluation" name="course_selection">
EOC2;
    echo $sessionObj->evaluation;
    echo <<<EOC3
</form>
EOC3;

    WebSitePageHandle::endBodyTags();
}