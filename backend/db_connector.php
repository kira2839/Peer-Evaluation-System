<?php

class DBConnector
{
    private $serverName = "tethys.cse.buffalo.edu";
    private $userName = "kuduvago";
    private $password = "50290281";
    private $databaseName = "kuduvago_db";

    private $dbConnection = NULL;

    function __construct()
    {
        $this->dbConnect();
    }

    function __destruct()
    {
        if (isset($this->dbConnection)) {
            echo "Disconnecting from DB ...\r\n";
            $this->dbConnection->close();
        }
    }

    private function dbConnect()
    {
        echo "Connecting to DB ...\r\n";
        // Create connection
        $this->dbConnection = mysqli_connect($this->serverName, $this->userName, $this->password, $this->databaseName);

        // Check connection
        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
    }

    public function getDBConnection() {
        return $this->dbConnection;
    }

    public static function putHello(){
        echo "<h1> Hello world</h1>";
    }
}


