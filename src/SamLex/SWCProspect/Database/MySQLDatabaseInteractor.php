<?php

namespace SamLex\SWCProspect\Database;

class MySQLDatabaseInteractor implements DatabaseInteractor
{
    private $mysql_con;

    public function __construct($address, $username, $password, $database)
    {
        $this->mysql_con = new \mysqli($address, $username, $password, $database);

        if ($this->mysql_con->connect_error) {
            die('Could not connect to database: '.$this->mysql_con->connect_error.' ('.$this->mysql_con->connect_errno.')');
        }
    }

    public function __destruct()
    {
        $this->mysql_con->close();
    }
}
