<?php
include_once('db_connector.php');

//Use the static method getInstance to get the object.
class EvaluationMeaningModel
{
    const TABLE_NAME = "evaluation_meaning";
    const KEY_NAME_COLUMN = "key_name";
    const VALUE_0_COLUMN = "value_0";
    const VALUE_1_COLUMN = "value_1";
    const VALUE_2_COLUMN = "value_2";
    const VALUE_3_COLUMN = "value_3";
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

    public function insert($keyName, $value0, $value1, $value2, $value3)
    {
        $sql = "INSERT INTO " . self::TABLE_NAME .
            "(" . self::KEY_NAME_COLUMN . self::COMMA .
            self::VALUE_0_COLUMN . self::COMMA .
            self::VALUE_1_COLUMN . self::COMMA .
            self::VALUE_2_COLUMN . self::COMMA .
            self::VALUE_3_COLUMN . self::COMMA . ") values (?, ?, ?, ?, ?)";

        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_param('sssss', $keyName, $value0, $value1, $value2, $value3);
        $return = $stmt->execute();
        $stmt->close();
        return $return;
    }

    public function getAllData()
    {
        $keyName = NULL;
        $value0 = NULL;
        $value1 = NULL;
        $value2 = NULL;
        $value3 = NULL;
        $evaluationMeaning = array();
        $oneRow = array();

        //Select confirmation code from student table
        $sql = "SELECT * FROM " .
            self::TABLE_NAME . " ORDER BY " . self::KEY_NAME_COLUMN;

        echo $sql;
        $stmt = $this->dbConnector->getDBConnection()->prepare($sql);
        $stmt->bind_result($keyName, $value0, $value1, $value2, $value3);
        $result = $stmt->execute();
        echo "test";
        if ($result === false) {
            return false;
        }
        echo "ff";

        while ($stmt->fetch()) {
            array_push($oneRow, $keyName, $value0, $value1, $value2, $value3);
            array_push($evaluationMeaning, $oneRow);
            $oneRow = array();
        }

        $stmt->close();
        print_r($evaluationMeaning);
        return $evaluationMeaning;
    }
}