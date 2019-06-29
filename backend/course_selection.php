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
    $class_corner = NULL;
    if($index === 0) {
        $class_corner = "ui-corner-top";
    }
    if($index === (count($course_names) - 1)){
        $class_corner = $class_corner . " " . "ui-corner-bottom";
    }
    echo <<< EOC2
    <button name='course_name' class="ui-button $class_corner ui-widget" value="$course_name" style="padding-left: 150px; padding-right: 150px;">
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
