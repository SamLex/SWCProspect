<?php

/** Part of SWCProspect, contains Deposit class. */
namespace SamLex\SWCProspect;

use SamLex\SWCProspect\Database\DatabaseInteractor;

/** Data class that holds data on a planet. */
class Planet
{
    /**
     * The database ID of this planet.
     *
     * @var int
     */
    private $dbID;

    /**
     * The name of this planet.
     *
     * @var string
     */
    private $name;

    /**
     * The size of this planet.
     *
     * @var int
     */
    private $size;

    /**
     * The type of this planet.
     *
     * @var PlanetType
     */
    private $planetType;

    /**
     * The database this planet instance was got from.
     *
     * @var DatabaseInteractor
     */
    private $db;

    /**
     * Constructs a new Planet instance.
     *
     * @param string             $name       The name of the planet.
     * @param int                $size       The size of this planet.
     * @param PlanetType         $planetType The type of this planet.
     * @param DatabaseInteractor $db         The source database.
     * @param int                $id         Optional paramater that should only be used by the DatabaseInteractor.
     */
    public function __construct($name, $size, $planetType, $db, $id = -1)
    {
        $this->dbID = $id;
        $this->name = $name;
        $this->size = $size;
        $this->planetType = $planetType;

        $this->db = $db;
    }

    /**
     * Save this planet to the source database.
     */
    public function save()
    {
        // If deposit is new, will get assigned database ID.
        $this->dbID = $this->db->savePlanet($this);
    }

    /**
     * Delete this planet from the source database.
     */
    public function delete()
    {
        // If deposit is existing, will get database ID removed.
        $this->dbID = $this->db->deletePlanet($this);
    }

    /**
     * Returns the database ID for this planet.
     *
     * @return int
     */
    public function getDBID()
    {
        return $this->dbID;
    }

    /**
     * Returns the name of this planet.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the size of this planet.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns the type of this planet.
     *
     * @return PlanetType
     */
    public function getType()
    {
        return $this->planetType;
    }
}
