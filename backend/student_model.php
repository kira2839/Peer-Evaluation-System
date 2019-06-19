<?php
require('db_connector.php');

class StudentModel
{
    const TABLE_NAME = "student";
    const ID_COLUMN = "id";
    const EMAIL_ADDRESS_COLUMN = "email_address";
    const CONFIRMATION_COLUMN = "confirmation_code";
    const LAST_GENERATED_TIME_COLUMN = "last_generated_time";
    const IS_CODE_USED_COLUMN = "is_code_used";
    const COMMA = ",";
    const EQUAL = "=";
    const TIME_FOR_CODE_EXPIRY_IN_MIN = 15;

    private $dbConnector;

    function __construct()
    {
        $this->dbConnector = new DBConnector();
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
            self::LAST_GENERATED_TIME_COLUMN . self::EQUAL . "CURRENT_TIMESTAMP WHERE " .
            self::EMAIL_ADDRESS_COLUMN . self::EQUAL . "?";

        $confirmationCode = password_hash($confirmationCode, PASSWORD_DEFAULT);
        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ss', $confirmationCode, $email);
        $stmt->execute();
        $stmt->close();
    }

    public function getActiveConfirmationCode($email)
    {
        $code=NULL;
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

        while($stmt->fetch()){
            $stmt->close();
            return $code;
        }
        return false;
    }

    public function getConfirmationCode($email)
    {
        $code=NULL;
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

        while($stmt->fetch()){
            $stmt->close();
            return $code;
        }
        return false;
    }
}