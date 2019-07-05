<?php
include_once('db_connector.php');
include_once('student_model.php');
include_once('student_group_model.php');
include_once('evaluation_meaning_model.php');

class DBSetup
{
    private $dbConnector;

    function __construct()
    {
        $this->dbConnector = new DBConnector();
    }

    public function dropTables()
    {
        //Drop the student_evaluation Table
        $sql = "DROP TABLE IF EXISTS student_evaluation";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Dropped table student_evaluation successfully\r\n";
        } else {
            echo "Error dropping student_evaluation table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        //Drop the student_group Table
        $sql = "DROP TABLE IF EXISTS student_group";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Dropped table student_group successfully\r\n";
        } else {
            echo "Error dropping student_group table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        //Drop the Student Table
        $sql = "DROP TABLE IF EXISTS student";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Dropped table student successfully\r\n";
        } else {
            echo "Error dropping student table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        //Drop the evaluation_meaning Table
        $sql = "DROP TABLE IF EXISTS evaluation_meaning";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Dropped table evaluation_meaning successfully\r\n";
        } else {
            echo "Error dropping evaluation_meaning table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }
    }

    public function createTable()
    {
        $sql = "CREATE TABLE student(  
                   id INT UNSIGNED AUTO_INCREMENT,
                   email_address VARCHAR(255) NOT NULL,
                   student_name VARCHAR (255) NOT NULL,
                   confirmation_code VARCHAR(255),
                   last_generated_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                   is_code_used INT UNSIGNED NOT NULL DEFAULT 0,
                   CONSTRAINT PK_STUDENT_ID PRIMARY KEY (id),
                   CONSTRAINT PK_STUDENT_EMAIL_ID UNIQUE KEY (email_address)
                ) ENGINE=InnoDB";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Table student created successfully\r\n";
        } else {
            echo "\r\nError creating student table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        $sql = "CREATE TABLE student_group(  
               group_id INT UNSIGNED NOT NULL,
               fk_student_id INT UNSIGNED,
               course_name VARCHAR (255) NOT NULL,
               CONSTRAINT FK_STUDENT_GROUP_ID FOREIGN KEY (fk_student_id) REFERENCES student(id),
               CONSTRAINT PK_STUDENT_GROUP PRIMARY KEY (group_id, fk_student_id, course_name),
               CONSTRAINT PK_STUDENT_ID_UNIQUE UNIQUE (fk_student_id, course_name)
            ) ENGINE=InnoDB";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Table student_group created successfully\r\n";
        } else {
            echo "\r\nError creating student_group table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        $sql = "CREATE TABLE student_evaluation(  
               fk_student_id INT UNSIGNED,
               fk_group_member_id INT UNSIGNED,
               course_name VARCHAR (255),
               role INT UNSIGNED,
               leadership INT UNSIGNED,
               participation INT UNSIGNED,
               professionalism INT UNSIGNED,
               quality INT UNSIGNED,
               normalized_score FLOAT,
               CONSTRAINT FK_STUDENT_EVALUATION_ID FOREIGN KEY (fk_student_id) REFERENCES student(id),
               CONSTRAINT FK_STUDENT_EVALUATION_MEMBER_ID FOREIGN KEY (fk_group_member_id) REFERENCES student(id),
               CONSTRAINT PK_STUDENT_EVALUATION_GROUP PRIMARY KEY (fk_student_id, fk_group_member_id, course_name)
            ) ENGINE=InnoDB";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Table student_evaluation created successfully\r\n";
        } else {
            echo "\r\nError creating student_evaluation table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }

        $sql = "CREATE TABLE evaluation_meaning(  
               key_name varchar(255),
               value_0 varchar(255),
               value_1 varchar(255),
               value_2 varchar(255),
               value_3 varchar(255),
               CONSTRAINT PK_EVALUATION_MEANING PRIMARY KEY (key_name)
            ) ENGINE=InnoDB";
        if ($this->dbConnector->getDBConnection()->query($sql) === TRUE) {
            echo "Table evaluation_meaning created successfully\r\n";
        } else {
            echo "\r\nError creating evaluation_meaning table: " . $this->dbConnector->getDBConnection()->error . "\r\n";
        }
    }

    public function seedData()
    {
        StudentModel::getInstance()->seedData("smishra9@buffalo.edu", "Sid");
        StudentModel::getInstance()->seedData("a@buffalo.edu", "A");
        StudentModel::getInstance()->seedData("b@buffalo.edu", "B");
        StudentModel::getInstance()->seedData("gouthamt@buffalo.edu", "Goutham");
        StudentModel::getInstance()->seedData("kuduvago@buffalo.edu", "Karthik");
        StudentModel::getInstance()->seedData("c@buffalo.edu", "C");
        StudentModel::getInstance()->seedData("d@buffalo.edu", "D");
        StudentGroupModel::getInstance()->getGroupID(1,1);
        StudentGroupModel::getInstance()->getGroupID(1,2);
        StudentGroupModel::getInstance()->getGroupID(1,3);
        StudentGroupModel::getInstance()->getGroupID(1,4);
        StudentGroupModel::getInstance()->getGroupID(2,5);
        StudentGroupModel::getInstance()->getGroupID(2,6);
        StudentGroupModel::getInstance()->getGroupID(2,7);
    }

}

$dbSetup = new DBSetup();

//Uncomment this in case you want to drop the tables before creation
//$dbSetup->dropTables();

$dbSetup->createTable();

//Uncomment this if you want to seed the DB with sample data
//$dbSetup->seedData();