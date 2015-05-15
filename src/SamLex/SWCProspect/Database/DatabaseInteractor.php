<?php

namespace SamLex\SWCProspect\Database;

/*
    Interface defining behaviour expected for back-end database/datasource
*/
interface DatabaseInteractor
{
    // Is the database available
    // 'Available' being likely to be able to return data
    public function isAvailable();

    // Get the deposit type with the given unique id and return as DepositType object
    // Return false on error
    public function getDepositType($id);

    // Get the planet type with the given unique id and return as PlanetType object
    // Return false on error
    public function getPlanetType($id);

    // Get the deposit data with the given unique id and return as Deposit object
    // Return false on error
    public function getDeposit($id);

    // Get the planet data with the given unique id and return as Planet object
    // Return false on error
    public function getPlanet($id);

    // Get all available deposit types and return as array of DepositType objects
    // Return false on error
    public function getDepositTypes();
    
    // Get all available planet types and return as array of PlanetType objects
    // Return false on error
    public function getPlanetTypes();
    
    // Get all available planet data and return as array of Planet objects
    // Return false on error
    public function getPlanets();

    // Get all available deposit data associated with the planet with the given unique id and return as array of Deposit objects
    // Return false on error
    public function getDeposits($planetID);

    // Return the number of deposits associated with the planet with the given unique id
    // Return false on error
    public function getNumDeposits($planetID);

    // Save the given Planet object
    // A negative id within the object indicates a new record should be created
    // Returns the given object or, if a new record is created, a new object reflecting the change
    // Returns false on error
    public function savePlanet($planet);

    // Save the given Deposit object
    // A negative id within the object indicates a new record should be created
    // Returns the given object or, if a new record is created, a new object reflecting the change
    // Returns false on error
    public function saveDeposit($deposit);

    // Delete the given Planet object
    // Returns a new object with a negative unique id, or false on error
    // Deletion of a planet should result in the deletion of associated deposits
    public function deletePlanet($planet);

    // Delete the given Deposit object
    // Returns a new object with a negative unique id, or false on error
    public function deleteDeposit($deposit);
}
