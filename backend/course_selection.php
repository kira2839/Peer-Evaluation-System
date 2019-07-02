<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');
include_once("student_evaluation_model.php");
include_once('evaluation_handle.php');

// We get the created session instance which is created earlier then proceed or error out
$sessionObj = Session::getInstance();

//Start creating the html tags
WebSitePageHandle::includeSiteHeader();

if (!$sessionObj->isSessionValid()) {
    $sessionObj->destroy();
    $sessionObj->displaySessionExpiredMessage();
    return;
}

if (isset($_POST['course_name'])) {
    $sessionObj->course_name = $_POST['course_name'];

    //1. Student Group ID
    $group_id = StudentGroupModel::getInstance()->getGroupID($sessionObj->student_id, $sessionObj->course_name);
    $sessionObj->group_id = $group_id;

    //2. Student All Group Members IDs
    $group_members_id = StudentGroupModel::getInstance()->getGroupMembersID($group_id, $sessionObj->course_name);
    $sessionObj->group_members_id = $group_members_id;

    //3. Evaluation Meaning
    $group_members_names = array();

    foreach ($group_members_id as $val) {
        array_push($group_members_names, StudentModel::getInstance()->getStudentName($val));
    }
    $sessionObj->group_members_names = $group_members_names;

    $score_submit_for_student =
        StudentEvaluationModel::getInstance()->getScoreSubmitPerCourse($sessionObj->student_id, $sessionObj->course_name);
    if(count($score_submit_for_student) === 0) {
        WebSitePageHandle::redirectUser('edit_evaluation.php');
    } else {
        $sessionObj->evaluation = EvaluationHandle::getInstance()->displayEvaluation($sessionObj);
        WebSitePageHandle::redirectUser('display_evaluation.php');
    }

    return;
}

$course_names = StudentGroupModel::getInstance()->getCourseNames($sessionObj->student_id);

echo <<<EOC1
    <p class="show_msg"> Your courses </p>
    <form action='course_selection.php' id="course_selection" name="course_selection" method='post'>
EOC1;

foreach ($course_names as $index => $course_name) {
    $class_corner = NULL;
    if($index === 0) {
        $class_corner = "ui-corner-top";
    }
    if($index === (count($course_names) - 1)){
        $class_corner = $class_corner . " " . "ui-corner-bottom";
    }
    echo <<< EOC2
    <button name='course_name' class="ui-button $class_corner ui-widget" value="$course_name" style="width:375px;">
        $course_name
    </button>
<br>
EOC2;
}

echo <<<EOC3
</form>
<script>
    (function (global) {

        if(typeof (global) === "undefined") {
            throw new Error("window is undefined");
        }

        var _hash = "!";
        var noBackPlease = function () {
            global.location.href += "#";

            // making sure we have the fruit available for juice (^__^)
            global.setTimeout(function () {
                global.location.href += "!";
            }, 50);
        };

        global.onhashchange = function () {
            if (global.location.hash !== _hash) {
                global.location.hash = _hash;
            }
        };

        global.onload = function () {
            noBackPlease();

            // disables backspace on page except on input fields and textarea..
            document.body.onkeydown = function (e) {
                var elm = e.target.nodeName.toLowerCase();
                if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                    e.preventDefault();
                }
                // stopping event bubbling up the DOM tree..
                e.stopPropagation();
            };
        }

    })(window);
</script>
EOC3;
