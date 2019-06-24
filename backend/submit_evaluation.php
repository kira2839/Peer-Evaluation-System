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

if (isset($_POST['form_data'])) {
    $form_data = $_POST['form_data'];
    if (insertIntoTable($form_data)) {
        echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-circle-check" style="float: left; margin-right: .3em;"></span>
		Successfully submitted your evaluation. A confirmation email has been sent</p>
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
}

function insertIntoTable($form_data)
{
    $insertSuccess = true;
    for ($i = 0; $i < count($form_data); $i++) {
        $var0 = $form_data[$i][0];
        $var1 = $form_data[$i][1];
        $var2 = $form_data[$i][2];
        $var3 = $form_data[$i][3];
        $var4 = $form_data[$i][4];
        $var5 = $form_data[$i][5];
        $var6 = $form_data[$i][6];

        $studentEvaluationModel = StudentEvaluationModel::getInstance();
        if ($studentEvaluationModel->insert($var0, $var1, $var2, $var3, $var4, $var5, $var6) === true OR
            $studentEvaluationModel->update($var0, $var1, $var2, $var3, $var4, $var5, $var6) === true) {
            $insertSuccess = $insertSuccess & true;
        } else {
            $insertSuccess = $insertSuccess & false;
        }
    }
    return $insertSuccess;
}