<?php

class DBConnector
{
    private $serverName = "tethys.cse.buffalo.edu";
    private $userName = "kuduvago";
    private $password = "50290281";
    private $databaseName = "cse442_542_2019_summer_teama_db";

    private $dbConnection = NULL;

    function __construct()
    {
        $this->dbConnect();
    }

    function __destruct()
    {
        if (isset($this->dbConnection)) {
            $this->dbConnection->close();
        }
    }

    private function dbConnect()
    {
        // Create connection
        $this->dbConnection = new mysqli($this->serverName, $this->userName, $this->password, $this->databaseName);

        // Check connection
        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
    }

    public function getDBConnection()
    {
        return $this->dbConnection;
    }
}
