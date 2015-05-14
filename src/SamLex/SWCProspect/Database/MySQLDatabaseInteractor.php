<?php

namespace SamLex\SWCProspect\Database;

class MySQLDatabaseInteractor implements DatabaseInteractor
{
    private $mysql_con;
    private $available = false;

    public function __construct($address, $username, $password, $database)
    {
        $this->mysql_con = new \mysqli($address, $username, $password, $database);

        if ($this->mysql_con->connect_error) {
            $this->available = false;
        } else {
            $this->available = true;
        }
    }

    public function __destruct()
    {
        $this->mysql_con->close();
    }

    public function isAvailable()
    {
        return $this->available;
    }

    public function getDepositType($id)
    {
    }

    public function getPlanetType($id)
    {
    }

    public function getDeposit($id)
    {
    }

    public function getPlanet($id)
    {
    }

    public function getPlanets()
    {
    }

    public function getDeposits($planetID)
    {
    }

    public function getNumDeposits($planetID)
    {
    }

    public function savePlanet($planet)
    {
    }

    public function saveDeposit($deposit)
    {
    }

    public function deletePlanet($planet)
    {
    }

    public function deleteDeposit($deposit)
    {
    }
}
