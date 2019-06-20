<?php
include_once('db_connector.php');

//Use the static method getInstance to get the object.
class StudentModel
{
    const TABLE_NAME = "student";
    const ID_COLUMN = "id";
    const EMAIL_ADDRESS_COLUMN = "email_address";
    const CONFIRMATION_COLUMN = "confirmation_code";
    const LAST_GENERATED_TIME_COLUMN = "last_generated_time";
    const IS_CODE_USED_COLUMN = "is_code_used";
    const STUDENT_NAME_COLUMN = "student_name";
    const COMMA = ",";
    const EQUAL = "=";
    const TIME_FOR_CODE_EXPIRY_IN_MIN = 15;

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

    public function seedData($email, $name)
    {
        //Insert into student table
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::EMAIL_ADDRESS_COLUMN . self::COMMA . self::STUDENT_NAME_COLUMN . ") values (?, ?)";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ss', $email, $name);
        $return = $stmt->execute();
        $stmt->close();
        return $return;

    }

    public function insert($email, $confirmationCode)
    {
        //Insert into student table
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::EMAIL_ADDRESS_COLUMN . self::COMMA . self::CONFIRMATION_COLUMN . ") values (?, ?)";

        $confirmationCode = password_hash($confirmationCode, PASSWORD_DEFAULT);
        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ss', $email, $confirmationCode);
        $return = $stmt->execute();
        $stmt->close();
        return $return;

    }

    public function update($email, $confirmationCode)
    {
        //Update entry at student table
        $sql = "UPDATE " . self::TABLE_NAME .
            " SET " . self::CONFIRMATION_COLUMN . self::EQUAL . "? " . self::COMMA .
            self::LAST_GENERATED_TIME_COLUMN . self::EQUAL . "CURRENT_TIMESTAMP " .
            self::COMMA . self::IS_CODE_USED_COLUMN . self::EQUAL . " 0 WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . "?";

        $confirmationCode = password_hash($confirmationCode, PASSWORD_DEFAULT);
        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ss', $confirmationCode, $email);
        $stmt->execute();
        $stmt->close();
    }

    public function getActiveConfirmationCode($email)
    {
        $code = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::CONFIRMATION_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . "? AND " .
            self::LAST_GENERATED_TIME_COLUMN . " > NOW() - INTERVAL " .
            self::TIME_FOR_CODE_EXPIRY_IN_MIN . " MINUTE AND " . self::IS_CODE_USED_COLUMN .
            self::EQUAL . "0";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->bind_result($code);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $stmt->close();
            return $code;
        }
        return false;
    }

    public function getConfirmationCode($email)
    {
        $code = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::CONFIRMATION_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . " ? AND " .
            self::IS_CODE_USED_COLUMN . self::EQUAL . " 0 ";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->bind_result($code);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $stmt->close();
            return $code;
        }
        return false;
    }

    public function markConfirmationCodeAsUsed($email)
    {
        //Update entry at student table
        $sql = "UPDATE " . self::TABLE_NAME .
            " SET " . self::IS_CODE_USED_COLUMN . self::EQUAL . "1 WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . "?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->close();
    }

    public function getStudentId($email)
    {
        $id = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::ID_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . " ?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->bind_result($id);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $stmt->close();
            return $id;
        }
        return false;
    }

    public function getStudentName($id)
    {
        $studentName = NULL;
        //Select confirmation code from student table
        $sql = "SELECT " . self::STUDENT_NAME_COLUMN .
            " FROM " . self::TABLE_NAME . " WHERE " .
            self::ID_COLUMN . self::EQUAL . " ?";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('d', $id);
        $stmt->bind_result($studentName);
        $result = $stmt->execute();
        if ($result === false) {
            return false;
        }

        while ($stmt->fetch()) {
            $stmt->close();
            return $studentName;
        }
        return false;
    }
}