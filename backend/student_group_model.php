<?php
include_once('db_connector.php');

//Use the static method getInstance to get the object.
class StudentGroupModel
{
    const TABLE_NAME = "student_group";
    const GROUP_ID_COLUMN = "group_id";
    const STUDENT_ID_COLUMN = "fk_student_id";
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

    public function insert($groupID, $studentID)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::GROUP_ID_COLUMN . self::COMMA . self::STUDENT_ID_COLUMN . ") values (?, ?)";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dd', $groupID, $studentID);
        $return = $stmt->execute();
        $stmt->close();
        return $return;
    }

    public function update($groupID, $studentID)
    {
        //Update entry at student table
        $sql = "UPDATE " . self::TABLE_NAME .
            " SET " . self::GROUP_ID_COLUMN . self::EQUAL . "? " . self::COMMA .
            " WHERE " . self::STUDENT_ID_COLUMN . self::EQUAL . "?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('dd', $groupID, $studentID);
        $stmt->execute();
        $stmt->close();
    }

    public function getGroupID($studentID)
    {
        $groupID = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::GROUP_ID_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::STUDENT_ID_COLUMN . self::EQUAL . " ? ";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('d', $studentID);
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

    public function getGroupMembersID($groupID)
    {
        $id = NULL;
        $studentIDs = array();
        //Select confirmation code from student table
        $sql = "SELECT " . self::STUDENT_ID_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::GROUP_ID_COLUMN . self::EQUAL . " ?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('s', $groupID);
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
}