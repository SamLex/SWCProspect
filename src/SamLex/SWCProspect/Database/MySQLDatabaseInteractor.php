<?php

namespace SamLex\SWCProspect\Database;

use SamLex\SWCProspect\Deposit;
use SamLex\SWCProspect\DepositType;
use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\PlanetType;

/*
    Implements the DatabaseInteractor interface using MySQL

    See the interface for details on methods
*/
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
        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Material, HTMLColour FROM DepositType WHERE ID=? LIMIT 1;');

        if (!$sqlStmt) {
            return false;
        }

        if (!$sqlStmt->bind_param('i', $id)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($typeID, $mat, $colour)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->fetch()) {
            $sqlStmt->close();

            return false;
        }

        $sqlStmt->close();

        return new DepositType($typeID, $mat, $colour);
    }

    public function getPlanetType($id)
    {
        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Description, HTMLColour FROM PlanetType WHERE ID=? LIMIT 1;');

        if (!$sqlStmt) {
            die($this->mysql_con->error);

            return false;
        }

        if (!$sqlStmt->bind_param('i', $id)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($typeID, $des, $colour)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->fetch()) {
            $sqlStmt->close();

            return false;
        }

        $sqlStmt->close();

        return new PlanetType($typeID, $des, $colour);
    }

    public function getDeposit($id)
    {
        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Size, LocationX, LocationY, PlanetID, DepositTypeID FROM Deposit WHERE ID=? LIMIT 1;');

        if (!$sqlStmt) {
            return false;
        }

        if (!$sqlStmt->bind_param('i', $id)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($depositID, $size, $locX, $locY, $planetID, $typeID)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->fetch()) {
            $sqlStmt->close();

            return false;
        }

        $sqlStmt->close();

        $planet = $this->getPlanet($planetID);
        $type = $this->getDepositType($typeID);

        if (!$planet) {
            return false;
        }

        if (!$type) {
            return false;
        }

        return new Deposit($size, $locX, $locY, $planet, $type, $this, $depositID);
    }

    public function getPlanet($id)
    {
        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Name, Size, PlanetTypeID FROM Planet WHERE ID=? LIMIT 1;');

        if (!$sqlStmt) {
            return false;
        }

        if (!$sqlStmt->bind_param('i', $id)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($planetID, $name, $size, $typeID)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->fetch()) {
            $sqlStmt->close();

            return false;
        }

        $sqlStmt->close();

        $type = $this->getPlanetType($typeID);

        if (!$type) {
            return false;
        }

        return new Planet($name, $size, $type, $this, $planetID);
    }

    public function getPlanets()
    {
        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Name, Size, PlanetTypeID FROM Planet;');

        if (!$sqlStmt) {
            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($planetID, $name, $size, $typeID)) {
            $sqlStmt->close();

            return false;
        }

        $planets = array();

        while ($sqlStmt->fetch()) {
            $type = $this->getPlanetType($typeID);

            if (!$type) {
                continue;
            }

            $planets[] = new Planet($name, $size, $type, $this, $planetID);
        }

        $sqlStmt->close();

        return $planets;
    }

    public function getDeposits($planetID)
    {
        $planet = $this->getPlanet($planetID);

        if (!$planet) {
            return false;
        }

        $sqlStmt = $this->mysql_con->prepare('SELECT ID, Size, LocationX, LocationY, DepositTypeID FROM Deposit WHERE PlanetID=?;');

        if (!$sqlStmt) {
            return false;
        }

        if (!$sqlStmt->bind_param('i', $planetID)) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($depositID, $size, $locX, $locY, $typeID)) {
            $sqlStmt->close();

            return false;
        }

        $deposits = array();

        while ($sqlStmt->fetch()) {
            $type = $this->getDepositType($typeID);

            if (!$type) {
                continue;
            }

            $deposits[] = new Deposit($size, $locX, $locY, $planet, $type, $this, $depositID);
        }

        $sqlStmt->close();

        return $deposits;
    }

    public function getNumDeposits($planetID)
    {
        $sqlStmt = $this->mysql_con->prepare('SELECT COUNT(ID) FROM Deposit WHERE PlanetID=? LIMIT 1;');

        if (!$sqlStmt) {
            return 0;
        }

        if (!$sqlStmt->bind_param('i', $planetID)) {
            $sqlStmt->close();

            return 0;
        }

        if (!$sqlStmt->execute()) {
            $sqlStmt->close();

            return 0;
        }

        if (!$sqlStmt->store_result()) {
            $sqlStmt->close();

            return false;
        }

        if (!$sqlStmt->bind_result($numDeposits)) {
            $sqlStmt->close();

            return 0;
        }

        if (!$sqlStmt->fetch()) {
            $sqlStmt->close();

            return 0;
        }

        $sqlStmt->close();

        return $numDeposits;
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
