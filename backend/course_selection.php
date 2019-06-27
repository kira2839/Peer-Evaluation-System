<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

//Start creating the html tags
WebSitePageHandle::includeSiteHeader();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

$course_names = StudentGroupModel::getInstance()->getCourseNames($sessionObj->student_id);

echo <<<EOC1
    <p class="show_msg"> Your courses </p>
    <form action='start_evaluation.php' id="course_selection" name="course_selection" method='post'>
EOC1;

foreach ($course_names as $index => $course_name) {
    echo <<< EOC2
    <button name='course_name' class="ui-button ui-corner-all ui-widget" value="$course_name" style="padding-left: 100px; padding-right: 100px;">
        $course_name
    </button>
<br>
EOC2;
}

echo <<<EOC3
</form>
EOC3;
