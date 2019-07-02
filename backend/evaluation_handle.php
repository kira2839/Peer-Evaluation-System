<?php
include_once("student_evaluation_model.php");

//Use the static method getInstance to get the object.
class EvaluationHandle
{
    // The only instance of the class
    private static $instance;

    private function __construct()
    {
    }

    /**
     *    Returns The instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     * @return    object
     **/
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function displayButtons()
    {
        echo <<<EOC
    <div style="display: inline-block;">
    <a class="bar_li about orange" style="background: #168433;" href="edit_evaluation.php">Edit</a>
    <a class="bar_li about orange" style="background: #CC852A;" href="course_selection.php">Change Course</a>
	</div><br>
EOC;
    }

    public function displayEvaluation($sessionObj)
    {
        if (isset($sessionObj->group_members_names) AND
            isset($sessionObj->student_id)) {
            $group_members_names = $sessionObj->group_members_names;
            $evaluation = NULL;
            ob_start();
            foreach ($sessionObj->group_members_id as $index => $group_member) {
                $evaluation_per_member = StudentEvaluationModel::getInstance()->getEvaluationPerGroupMember(
                    $sessionObj->student_id, $group_member, $sessionObj->course_name);
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
</div>
<br>
EOC3;
            }
            $evaluation = ob_get_contents();
            ob_end_clean();
            return $evaluation;
        }
        return NULL;
    }

    public function insertIntoTable($form_data)
    {
        global $sessionObj;
        $form_data = self::calculateNormalizedScore($form_data);

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

    public function calculateNormalizedScore($form_data)
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
}