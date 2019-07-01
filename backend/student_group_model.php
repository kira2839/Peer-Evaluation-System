<?php
include_once('db_connector.php');

//Use the static method getInstance to get the object.
class StudentGroupModel
{
    const TABLE_NAME = "student_group";
    const GROUP_ID_COLUMN = "group_id";
    const STUDENT_ID_COLUMN = "fk_student_id";
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

    public function insert($groupID, $studentID, $courseName)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::GROUP_ID_COLUMN . self::COMMA . self::STUDENT_ID_COLUMN .
            self::COMMA . self::COURSE_NAME_COLUMN . ") values (?, ?, ?)";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dds', $groupID, $studentID, $courseName);
        $return = $stmt->execute();
        $stmt->close();
        return $return;
    }

    public function update($groupID, $studentID, $courseName)
    {
        //Update entry at student table
        $sql = "UPDATE " . self::TABLE_NAME .
            " SET " . self::GROUP_ID_COLUMN . self::EQUAL . "?" .
            self::COMMA . self::COURSE_NAME_COLUMN . self::EQUAL . "?" .
            " WHERE " . self::STUDENT_ID_COLUMN . self::EQUAL . "?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dds', $groupID, $studentID, $courseName);
        $stmt->execute();
        $stmt->close();
    }

    public function getGroupID($studentID, $courseName)
    {
        $groupID = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::GROUP_ID_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::STUDENT_ID_COLUMN . self::EQUAL . " ? AND " .
            self::COURSE_NAME_COLUMN . self::EQUAL . "?" ;

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ds', $studentID, $courseName);
        $stmt->bind_result($groupID);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $stmt->close();
            return $groupID;
        }
        return false;
    }

    public function getGroupMembersID($groupID, $courseName)
    {
        $id = NULL;
        $studentIDs = array();
        //Select confirmation code from student table
        $sql = "SELECT " . self::STUDENT_ID_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::GROUP_ID_COLUMN . self::EQUAL . " ? AND " .
            self::COURSE_NAME_COLUMN . self::EQUAL . "?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ds', $groupID, $courseName);
        $stmt->bind_result($id);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            array_push($studentIDs, $id);
        }

        $stmt->close();
        return $studentIDs;
    }

    public function getCourseNames($studentId)
    {
        $courseName = NULL;
        $courseNames = array();
        //Select confirmation code from student table
        $sql = "SELECT DISTINCT(" . self::COURSE_NAME_COLUMN .
            ") FROM " . self::TABLE_NAME . " WHERE " .
            self::STUDENT_ID_COLUMN . self::EQUAL . " ? " ;

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('d', $studentId);
        $stmt->bind_result($courseName);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            array_push($courseNames, $courseName);
        }

        $stmt->close();
        return $courseNames;
    }
}