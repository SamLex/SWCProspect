<?php

/** Part of SWCProspect, contains DatabaseInteractor interface. */
namespace SamLex\SWCProspect\Database;

use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\PlanetType;
use SamLex\SWCProspect\Deposit;
use SamLex\SWCProspect\DepositType;

/** Interface defining behaviour expected for back-end database/datasource. */
interface DatabaseInteractor
{
    /**
     * Initializes the database interactor.
     *
     * @return bool True if the DI initialized successfully.
     */
    public function init();

    /**
     * Returns an array of all known planets.
     *
     * @return Planet[]
     */
    public function getPlanets();

    /**
     * Returns the type with the given id.
     *
     * @param int $typeID
     *
     * @return PlanetType
     */
    public function getPlanetType($typeID);

    /**
     * Returns the number of deposits on the given planet.
     *
     * @param int $planetID
     *
     * @return int
     */
    public function getNumDeposits($planetID);

    /**
     * Returns an array of all known planet types.
     *
     * @return PlanetType[]
     */
    public function getPlanetTypes();

    /**
     * Returns the planet with the given id.
     *
     * @param int $planetID
     *
     * @return Planet
     */
    public function getPlanet($planetID);

    /**
     * Returns an array of all deposits on the given planet.
     *
     * @param int $planetID
     *
     * @return Deposit[]
     */
    public function getDeposits($planetID);

    /**
     * Returns the type with the given id.
     *
     * @param int $typeID
     *
     * @return DepositType
     */
    public function getDepositType($typeID);

    /**
     * Returns an array of all known deposit types.
     *
     * @return DepositType[]
     */
    public function getDepositTypes();

    /**
     * Saves the given deposit to the database.
     *
     * @param Deposit $deposit
     *
     * @return int The newly assigned DBID if it gets one, or the current one
     */
    public function saveDeposit($deposit);

    /**
     * Returns the deposit the given id.
     *
     * @param int $depositID
     *
     * @return Deposit
     */
    public function getDeposit($depositID);

    /**
     * Saves the given planet to the database.
     *
     * @param Planet $planet
     *
     * @return int The newly assigned DBID if it gets one, or the current one
     */
    public function savePlanet($planet);

    /**
     * Deletes the given deposit from the database.
     *
     * @param Deposit $deposit
     *
     * @return int Negative number on success, current DBID on failure
     */
    public function deleteDeposit($deposit);

    /**
     * Deletes the given planet from the database.
     *
     * @param Planet $planet
     *
     * @return int Negative number on success, current DBID on failure
     */
    public function deletePlanet($planet);
}
