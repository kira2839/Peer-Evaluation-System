<?php

include_once('website_page_handle.php');
include_once('session.php');
include_once("student_evaluation_model.php");
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
		Course is not being selected</p>
	</div>
EOC;
    return;
}

if (isset($_POST['form_data'])) {
    $form_data = $_POST['form_data'];
    if (insertIntoTable($form_data)) {
        echo <<<EOC
    <div id="display_evaluation" class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-circle-check" style="float: left; margin-right: .3em;"></span>
		Successfully submitted your evaluation for $sessionObj->course_name. A confirmation email has been sent</p>
	</div> <br> <br>
EOC;
        $evaluation_per_member = StudentEvaluationModel::getInstance()->getEvaluation($sessionObj->student_id, 1);
        $email = new Email($sessionObj->email_id);
        $group_members_names = $sessionObj->group_members_names;
        $evaluation = NULL;
        ob_start();
        foreach ($sessionObj->group_members_id as $index => $group_member) {
            $evaluation_per_member = StudentEvaluationModel::getInstance()->getEvaluation($sessionObj->student_id, $group_member);
            echo <<< EOC1
    <li id="group_member_$index" value="$group_member" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-top">$group_members_names[$index]</li>
    <table class="ui-datepicker-calendar ui-widget ui-widget-content ui-corner-bottom" style="width:100%">
      <tr>
        <th>Category</th>
        <th>Ratings</th> 
      </tr>
EOC1;
            foreach ($sessionObj->evaluation_meaning as $catIndex => $ratings) {
                $ratingIndex = $evaluation_per_member[$catIndex] + 1;
                echo <<< EOC2
        <tr>
        <td>$ratings[0]</td>
        <td>$ratings[$ratingIndex]</td>
        </tr>
EOC2;
            }
            echo <<< EOC3
    </table>
<br>
EOC3;
        }
        $evaluation = ob_get_contents();
        ob_end_clean();
        echo $evaluation;
        $email->sendAckMailOnEvaluation($evaluation);
    } else {
        echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Failed to submit your evaluation. Please try again</p>
	</div>
EOC;
    }
    //$sessionObj->destroy();
    return "success";
}

function insertIntoTable($form_data)
{
    global $sessionObj;
    $form_data = calculateNormalizedScore($form_data);

    $insertSuccess = true;
    for ($i = 0; $i < count($form_data); $i++) {
        $student_id = $form_data[$i][0];
        $group_member_id = $form_data[$i][1];
        $leadership = $form_data[$i][2];
        $participation = $form_data[$i][3];
        $professionalism = $form_data[$i][4];
        $quality = $form_data[$i][5];
        $role = $form_data[$i][6];
        $normalized_score = $form_data[$i][7];

        $studentEvaluationModel = StudentEvaluationModel::getInstance();
        if ($studentEvaluationModel->insert($student_id, $group_member_id, $sessionObj->course_name, $role,
                $leadership, $participation, $professionalism, $quality, $normalized_score) === true OR
            $studentEvaluationModel->update($student_id, $group_member_id, $sessionObj->course_name, $role,
                $leadership, $participation, $professionalism, $quality, $normalized_score) === true) {
            $insertSuccess = $insertSuccess & true;
        } else {
            $insertSuccess = $insertSuccess & false;
        }
    }
    return $insertSuccess;
}

function calculateNormalizedScore($form_data)
{
    $total = 0;

    //Calculate the total for each member and the total for the evaluation
    for ($i = 0; $i < count($form_data); $i++) {
        $per_member_total = $form_data[$i][2] + $form_data[$i][3] + $form_data[$i][4] +
            $form_data[$i][5] + $form_data[$i][6];
        array_push($form_data[$i], $per_member_total);
        $total = $total + $per_member_total;
    }

    //Calculate the normalized score for each member
    for ($i = 0; $i < count($form_data); $i++) {
        $form_data[$i][7] = $form_data[$i][7] / $total;
    }

    return $form_data;
}