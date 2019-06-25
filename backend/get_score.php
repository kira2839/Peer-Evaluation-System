<?php
include_once('student_evaluation_model.php');
include_once('student_model.php');

//Use the static method getInstance to get the object.
class GetScore
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

    public function getPerCourse() {
        $result = StudentEvaluationModel::getInstance()->getScorePerCourse(1);
        echo "student_email_id,evaluation_submitted_by,role,leadership,participation,professionalism,quality,normalized_score\r\n" ;
        foreach ($result as $index => $each_row) {
            $evaluation_given_by = StudentModel::getInstance()->getStudentEmail($each_row[0]);
            $each_row[0] = StudentModel::getInstance()->getStudentEmail($each_row[1]);
            $each_row[1] = $evaluation_given_by;
            foreach ($each_row as $catIndex => $each_col) {
                echo $each_col;
                if($catIndex != (count($each_row) - 1)) {
                    echo ",";
                }
            }
            echo "\r\n";
        }
    }
}

GetScore::getInstance()->getPerCourse();