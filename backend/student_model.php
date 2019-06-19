<?php
require('db_connector.php');

class StudentModel
{
    const TABLE_NAME = "student";
    const ID_COLUMN = "id";
    const EMAIL_ADDRESS_COLUMN = "email_address";
    const CONFIRMATION_COLUMN = "confirmation_code";
    const LAST_GENERATED_TIME_COLUMN = "last_generated_time";
    const COMMA = ",";

    private $dbConnector;

    function __construct()
    {
        $this->dbConnector = new DBConnector();
    }

    public function insert($email, $confirmationCode)
    {
        //Drop the student_evaluation Table
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::EMAIL_ADDRESS_COLUMN . self::COMMA . self::CONFIRMATION_COLUMN . ") values (?, ?)";

        echo $sql;
        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('ss', $email, $confirmationCode);
        $stmt->execute();
        $stmt->close();
    }
}