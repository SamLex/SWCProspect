<?php

/** Part of SWCProspect, contains MySQLDatabaseInteractor class. */
namespace SamLex\SWCProspect\Database;

use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\PlanetType;
use SamLex\SWCProspect\Deposit;
use SamLex\SWCProspect\DepositType;

/** Implements the DatabaseInteractor interface using MySQL. */
class MySQLDatabaseInteractor implements DatabaseInteractor
{
    /** SQL statement used to get all planets. */
    private $getPlanetsSQL = 'SELECT ID, Name, Size, PlanetTypeID FROM Planet';

    /** SQL statement used to get a planet type. */
    private $getPlanetTypeSQL = 'SELECT ID, Description, HTMLColour FROM PlanetType WHERE ID=? LIMIT 1';

    /** SQL statement used to get the number of deposits on a planet */
    private $getNumDepositsSQL = 'SELECT COUNT(ID) FROM Deposit WHERE PlanetID=? LIMIT 1';

    /** SQL statement used to get all planet types. */
    private $getPlanetTypesSQL = 'SELECT ID, Description, HTMLColour FROM PlanetType';

    /** SQL statement used to get a planet. */
    private $getPlanetSQL = 'SELECT ID, Name, Size, PlanetTypeID FROM Planet WHERE ID=? LIMIT 1';

    /** SQL statement used to get all deposits on a planet. */
    private $getDepositsSQL = 'SELECT ID, Size, LocationX, LocationY, DepositTypeID FROM Deposit WHERE PlanetID=?';

    /** SQL statement used to get a deposit type. */
    private $getDepositTypeSQL = 'SELECT ID, Material, HTMLColour FROM DepositType WHERE ID=? LIMIT 1';

    /** SQL statement used to get all deposit types. */
    private $getDepositTypesSQL = 'SELECT ID, Material, HTMLColour FROM DepositType';

    /** SQL statement used to save a new deposit. */
    private $saveDepositNewSQL = 'INSERT INTO Deposit (Size, LocationX, LocationY, PlanetID, DepositTypeID) VALUES (?,?,?,?,?)';

    /** SQL statement used to save an existing deposit. */
    private $saveDepositExistingSQL = 'UPDATE Deposit SET Size=?, LocationX=?, LocationY=?, PlanetID=?, DepositTypeID=? WHERE ID=?';

    /** SQL statement used to get a deposit. */
    private $getDepositSQL = 'SELECT ID, Size, LocationX, LocationY, PlanetID, DepositTypeID FROM Deposit WHERE ID=? LIMIT 1';

    /** SQL statement used to save a new planet. */
    private $savePlanetNewSQL = 'INSERT INTO Planet (Name, Size, PlanetTypeID) VALUES (?,?,?)';

    /** SQL statement used to save an existing planet. */
    private $savePlanetExistingSQL = 'UPDATE Planet SET Name=?, Size=?, PlanetTypeID=? WHERE ID=?';

    /** SQL statement used to delete an existing deposit. */
    private $deleteDepositSQL = 'DELETE FROM Deposit WHERE ID=?';

    /** SQL statement used to delete an existing planet. */
    private $deletePlanetSQL = 'DELETE FROM Planet WHERE ID=?';

    /**
     * The address of the MySQL server.
     *
     * @var string
     */
    private $mysqlAddress = '';

    /**
     * The username to connect to the MySQL server with.
     *
     * @var string
     */
    private $mysqlUsername = '';

    /**
     * The password to connect to the MySQL server with.
     *
     * @var string
     */
    private $mysqlPassword = '';

    /**
     * The database to use on the MySQL server.
     *
     * @var string
     */
    private $mysqlDatabase = '';

    /**
     * The active MySQL connection.
     *
     * @var \mysqli
     */
    private $mysql_con = null;

    /**
     * Constructs a new MySQLDatabaseInteractor instance.
     *
     * @param string $address  The address of the MySQL server.
     * @param string $username The username to connect to the MySQL server with.
     * @param string $password The password to connect to the MySQL server with.
     * @param string $database The database to use on the MySQL server.
     */
    public function __construct($address, $username, $password, $database)
    {
        $this->mysqlAddress = $address;
        $this->mysqlUsername = $username;
        $this->mysqlPassword = $password;
        $this->mysqlDatabase = $database;

        $this->mysql_con = new \mysqli($address, $username, $password, $database);

        if ($this->mysql_con->connect_error) {
            $this->available = false;
        } else {
            $this->available = true;
        }
    }

    /**
     * Initializes the MySQL database connection.
     *
     * @return bool True if the connection initialized successfully.
     */
    public function init()
    {
        $this->mysql_con = new \mysqli($this->mysqlAddress, $this->mysqlUsername, $this->mysqlPassword, $this->mysqlDatabase);

        if (!is_null($this->mysql_con->connect_error)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Deconstructs the instance.
     */
    public function __destruct()
    {
        // Ensure connection is closed nicely.
        if (!is_null($this->mysql_con)) {
            $this->mysql_con->close();
        }
    }

    /**
     * Returns an array of all known planets.
     *
     * @return Planet[]
     */
    public function getPlanets()
    {
        $planets = array();
        $stmt = $this->mysql_con->prepare($this->getPlanetsSQL);

        if ($stmt !== false) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($planetID, $name, $size, $typeID);

            while ($stmt->fetch()) {
                $type = $this->getPlanetType($typeID);

                if (!is_null($type)) {
                    $planets[] = new Planet($name, $size, $type, $this, $planetID);
                }
            }

            $stmt->close();
        }

        return $planets;
    }

    /**
     * Returns the type with the given id.
     *
     * @param int $typeID
     *
     * @return PlanetType
     */
    public function getPlanetType($id)
    {
        $type = null;
        $stmt = $this->mysql_con->prepare($this->getPlanetTypeSQL);

        if ($stmt !== false) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($typeID, $des, $colour);

            while ($stmt->fetch()) {
                $type = new PlanetType($typeID, $des, $colour);
            }

            $stmt->close();
        }

        return $type;
    }

    /**
     * Returns the number of deposits on the given planet.
     *
     * @param int $planetID
     *
     * @return int
     */
    public function getNumDeposits($planetID)
    {
        $num = 0;
        $stmt = $this->mysql_con->prepare($this->getNumDepositsSQL);

        if ($stmt !== false) {
            $stmt->bind_param('i', $planetID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($numDeposits);

            while ($stmt->fetch()) {
                $num = $numDeposits;
            }

            $stmt->close();
        }

        return $num;
    }

    /**
     * Returns an array of all known planet types.
     *
     * @return PlanetType[]
     */
    public function getPlanetTypes()
    {
        $types = array();
        $stmt = $this->mysql_con->prepare($this->getPlanetTypesSQL);

        if ($stmt !== false) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($planetTypeID, $des, $colour);

            while ($stmt->fetch()) {
                $types[] = new PlanetType($planetTypeID, $des, $colour);
            }

            $stmt->close();
        }

        return $types;
    }

    /**
     * Returns the planet with the given id.
     *
     * @param int $planetID
     *
     * @return Planet
     */
    public function getPlanet($planetID)
    {
        $planet = null;
        $stmt = $this->mysql_con->prepare($this->getPlanetSQL);

        if ($stmt !== false) {
            $stmt->bind_param('i', $planetID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $name, $size, $typeID);

            while ($stmt->fetch()) {
                $type = $this->getPlanetType($typeID);

                if (!is_null($type)) {
                    $planet = new Planet($name, $size, $type, $this, $id);
                }
            }

            $stmt->close();
        }

        return $planet;
    }

    /**
     * Returns an array of all deposits on the given planet.
     *
     * @param int $planetID
     *
     * @return Deposit[]
     */
    public function getDeposits($planetID)
    {
        $deposits = array();
        $planet = $this->getPlanet($planetID);
        $stmt = $this->mysql_con->prepare($this->getDepositsSQL);

        if ($stmt !== false && !is_null($planet)) {
            $stmt->bind_param('i', $planetID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($depositID, $size, $locX, $locY, $typeID);

            while ($stmt->fetch()) {
                $type = $this->getDepositType($typeID);

                if (!is_null($type)) {
                    $deposits[] = new Deposit($size, $locX, $locY, $planet, $type, $this, $depositID);
                }
            }

            $stmt->close();
        }

        return $deposits;
    }

    /**
     * Returns the type with the given id.
     *
     * @param int $typeID
     *
     * @return DepositType
     */
    public function getDepositType($typeID)
    {
        $type = null;
        $stmt = $this->mysql_con->prepare($this->getDepositTypeSQL);

        if ($stmt !== false) {
            $stmt->bind_param('i', $typeID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $mat, $colour);

            while ($stmt->fetch()) {
                $type = new DepositType($id, $mat, $colour);
            }

            $stmt->close();
        }

        return $type;
    }

    /**
     * Returns an array of all known deposit types.
     *
     * @return DepositType[]
     */
    public function getDepositTypes()
    {
        $types = array();
        $stmt = $this->mysql_con->prepare($this->getDepositTypesSQL);

        if ($stmt !== false) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($depositTypeID, $mat, $colour);

            while ($stmt->fetch()) {
                $types[] = new DepositType($depositTypeID, $mat, $colour);
            }

            $stmt->close();
        }

        return $types;
    }

    /**
     * Saves the given deposit to the database.
     *
     * @param Deposit $deposit
     *
     * @return int The newly assigned DBID if it gets one, or the current one
     */
    public function saveDeposit($deposit)
    {
        // Unpack object for SQL binding
        $depositID = $deposit->getDBID();
        $depositSize = $deposit->getSize();
        $depositLocX = $deposit->getLocationX();
        $depositLocY = $deposit->getLocationY();
        $planetID = $deposit->getPlanet()->getDBID();
        $typeID = $deposit->getType()->getDBID();

        if ($depositID < 0) {
            $stmt = $this->mysql_con->prepare($this->saveDepositNewSQL);
        } else {
            $stmt = $this->mysql_con->prepare($this->saveDepositExistingSQL);
        }

        if ($stmt !== false) {
            if ($depositID < 0) {
                $stmt->bind_param('iiiii', $depositSize, $depositLocX, $depositLocY, $planetID, $typeID);
            } else {
                $stmt->bind_param('iiiiii', $depositSize, $depositLocX, $depositLocY, $planetID, $typeID, $depositID);
            }

            if ($stmt->execute() === true && $depositID < 0) {
                $depositID = $stmt->insert_id;
            }

            $stmt->close();
        }

        return $depositID;
    }

    /**
     * Returns the deposit the given id.
     *
     * @param int $depositID
     *
     * @return Deposit
     */
    public function getDeposit($depositID)
    {
        $deposit = null;
        $stmt = $this->mysql_con->prepare($this->getDepositSQL);

        if ($stmt !== false) {
            $stmt->bind_param('i', $depositID);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($depositID, $size, $locX, $locY, $planetID, $typeID);

            while ($stmt->fetch()) {
                $type = $this->getDepositType($typeID);
                $planet = $this->getPlanet($planetID);

                if (!is_null($type) && !is_null($planet)) {
                    $deposit = new Deposit($size, $locX, $locY, $planet, $type, $this, $depositID);
                }
            }

            $stmt->close();
        }

        return $deposit;
    }

    /**
     * Saves the given planet to the database.
     *
     * @param Planet $planet
     *
     * @return int The newly assigned DBID if it gets one, or the current one
     */
    public function savePlanet($planet)
    {
        $planetID = $planet->getDBID();
        $planetName = $planet->getName();
        $planetSize = $planet->getSize();
        $typeID = $planet->getType()->getDBID();

        if ($planetID < 0) {
            $stmt = $this->mysql_con->prepare($this->savePlanetNewSQL);
        } else {
            $stmt = $this->mysql_con->prepare($this->savePlanetExistingSQL);
        }

        if ($stmt !== false) {
            if ($planetID < 0) {
                $stmt->bind_param('sii', $planetName, $planetSize, $typeID);
            } else {
                $stmt->bind_param('siii', $planetName, $planetSize, $typeID, $planetID);
            }

            if ($stmt->execute() === true && $planetID < 0) {
                $planetID = $stmt->insert_id;
            }

            $stmt->close();
        }

        return $planetID;
    }

    /**
     * Deletes the given deposit from the database.
     *
     * @param Deposit $deposit
     *
     * @return int Negative number on success, current DBID on failure
     */
    public function deleteDeposit($deposit)
    {
        $depositID = $deposit->getDBID();

        if ($depositID > -1) {
            $stmt = $this->mysql_con->prepare($this->deleteDepositSQL);

            if ($stmt !== false) {
                $stmt->bind_param('i', $depositID);
            }

            if ($stmt->execute() === true) {
                $depositID = -1;
            }

            $stmt->close();
        }

        return $depositID;
    }

    /**
     * Deletes the given planet from the database.
     *
     * @param Planet $planet
     *
     * @return int Negative number on success, current DBID on failure
     */
    public function deletePlanet($planet)
    {
        $planetID = $planet->getDBID();

        if ($planetID > -1) {
            $stmt = $this->mysql_con->prepare($this->deletePlanetSQL);

            if ($stmt !== false) {
                $stmt->bind_param('i', $planetID);
            }

            if ($stmt->execute() === true) {
                $planetID = -1;
            }

            $stmt->close();
        }

        return $planetID;
    }
}
