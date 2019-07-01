<?php
include_once('db_connector.php');

//Use the static method getInstance to get the object.
class StudentEvaluationModel
{
    const TABLE_NAME = "student_evaluation";
    const STUDENT_ID_COLUMN = "fk_student_id";
    const GROUP_MEMBER_ID_COLUMN = "fk_group_member_id";
    const ROLE_COLUMN = "role";
    const LEADERSHIP_COLUMN = "leadership";
    const PARTICIPATION_COLUMN = "participation";
    const PROFESSIONALISM_COLUMN = "professionalism";
    const QUALITY_COLUMN = "quality";
    const NORMALIZED_SCORE_COLUMN = "normalized_score";
    const COURSE_NAME_COLUMN = "course_name";
    const COMMA = ",";
    const EQUAL = "=";

    private $dbConnector;

    // The only instance of the class
    private static $instance;

    private function __construct()
    {
        $this->dbConnector = new DBConnector();
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

    public function insert($studentID, $groupMemberID, $courseName, $role, $leadership, $participation, $professionalism, $quality,
                           $normalized_score)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::STUDENT_ID_COLUMN . self::COMMA . self::GROUP_MEMBER_ID_COLUMN .
            self::COMMA . self::COURSE_NAME_COLUMN .
            self::COMMA . self::ROLE_COLUMN . self::COMMA . self::LEADERSHIP_COLUMN .
            self::COMMA . self::PARTICIPATION_COLUMN . self::COMMA . self::PROFESSIONALISM_COLUMN .
            self::COMMA . self::QUALITY_COLUMN . self::COMMA . self::NORMALIZED_SCORE_COLUMN .
            ") values (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ddsdddddd', $studentID, $groupMemberID, $courseName, $role, $leadership, $participation,
            $professionalism, $quality, $normalized_score);
        $return = $stmt->execute();
        $stmt->close();
        return $return;
    }

    public function update($studentID, $groupMemberID, $courseName, $role, $leadership, $participation, $professionalism, $quality,
                           $normalized_score)
    {
        //Update entry at student table
        $sql = "UPDATE " . self::TABLE_NAME .
            " SET " . self::ROLE_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::LEADERSHIP_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::PARTICIPATION_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::PROFESSIONALISM_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::QUALITY_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::NORMALIZED_SCORE_COLUMN . self::EQUAL . "? WHERE " .
            self::STUDENT_ID_COLUMN . self::EQUAL . "? AND " .
            self::COURSE_NAME_COLUMN . self::EQUAL . "? AND " .
            self::GROUP_MEMBER_ID_COLUMN . self::EQUAL . "?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dddddddsd', $role, $leadership, $participation, $professionalism, $quality,
            $normalized_score, $studentID, $courseName, $groupMemberID);
        $return = $stmt->execute();
        $stmt->close();
        return $return;
    }

    public function getEvaluation($studentID, $groupMemberID, $courseName)
    {
        $role = NULL;
        $leadership = NULL;
        $participation = NULL;
        $professionalism = NULL;
        $quality = NULL;

        $evaluation = array();

        //Select confirmation code from student table
        $sql = "SELECT " . self::ROLE_COLUMN .
            self::COMMA . self::LEADERSHIP_COLUMN .
            self::COMMA . self::PARTICIPATION_COLUMN .
            self::COMMA . self::PROFESSIONALISM_COLUMN .
            self::COMMA . self::QUALITY_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::STUDENT_ID_COLUMN . self::EQUAL . "? AND " .
            self::GROUP_MEMBER_ID_COLUMN . self::EQUAL."? AND ".
            self::COURSE_NAME_COLUMN . self::EQUAL . "?";


        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dds', $studentID, $groupMemberID, $courseName);
        $result = $stmt->execute();
        $stmt->bind_result($role, $leadership, $participation, $professionalism, $quality);
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            array_push($evaluation, $role, $leadership, $participation, $professionalism, $quality);
            break;
        }

        $stmt->close();
        return $evaluation;
    }

    public function getScorePerCourse()
    {
        $student_id = NULL;
        $group_member_id = NULL;
        $course_name = NULL;
        $role = NULL;
        $leadership = NULL;
        $participation = NULL;
        $professionalism = NULL;
        $quality = NULL;
        $normalized_score = NULL;
        $evaluation = array();

        //Select confirmation code from student table
        $sql = "SELECT " . self::STUDENT_ID_COLUMN .
            self::COMMA . self::GROUP_MEMBER_ID_COLUMN .
            self::COMMA . self::COURSE_NAME_COLUMN .
            self::COMMA . self::ROLE_COLUMN .
            self::COMMA . self::LEADERSHIP_COLUMN .
            self::COMMA . self::PARTICIPATION_COLUMN .
            self::COMMA . self::PROFESSIONALISM_COLUMN .
            self::COMMA . self::QUALITY_COLUMN .
            self::COMMA . self::NORMALIZED_SCORE_COLUMN .
            " FROM " . self::TABLE_NAME;


        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_result($student_id, $group_member_id, $course_name, $role, $leadership, $participation,
            $professionalism, $quality, $normalized_score);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $one_row = array();
            array_push($one_row, $student_id, $group_member_id, $course_name, $role, $leadership,
                $participation, $professionalism, $quality, $normalized_score);
            array_push($evaluation, $one_row);
        }

        $stmt->close();
        return $evaluation;
    }
}