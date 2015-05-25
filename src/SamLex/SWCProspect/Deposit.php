<?php

/** Part of SWCProspect, contains Deposit class. */
namespace SamLex\SWCProspect;

use SamLex\SWCProspect\Database\DatabaseInteractor;

/** Data class that holds data on a deposit. */
class Deposit
{
    /**
     * The database ID of this deposit.
     *
     * @var int
     */
    private $dbID;

    /**
     * The size of this deposit.
     *
     * @var int
     */
    private $size;

    /**
     * The X coordinate of this deposit.
     *
     * @var int
     */
    private $locationX;

    /**
     * The Y coordinate of this deposit.
     *
     * @var int
     */
    private $locationY;

    /**
     * The planet this deposit is on.
     *
     * @var Planet
     */
    private $planet;

    /**
     * The type of this deposit.
     *
     * @var DepositType
     */
    private $depositType;

    /**
     * The database this deposit instance was got from.
     *
     * @var DatabaseInteractor
     */
    private $db;

    /**
     * Constructs a new Deposit instance.
     *
     * @param int                $size        The size of the deposit.
     * @param int                $locX        The X coordinate of the deposit.
     * @param int                $locY        The Y coordinate of the deposit.
     * @param Planet             $planet      The planet this deposit is on.
     * @param DepositType        $depositType The type of this deposit.
     * @param DatabaseInteractor $db          The source database.
     * @param int                $id          Optional paramater that should only be used by the DatabaseInteractor.
     */
    public function __construct($size, $locX, $locY, $planet, $depositType, $db, $id = -1)
    {
        $this->dbID = $id;
        $this->size = $size;
        $this->locationX = $locX;
        $this->locationY = $locY;
        $this->planet = $planet;
        $this->depositType = $depositType;

        $this->db = $db;
    }

    /**
     * Save this deposit to the source database.
     */
    public function save()
    {
        // If deposit is new, will get assigned database ID.
        $this->dbID = $this->db->saveDeposit($this);
    }

    /**
     * Delete this deposit from the source database.
     */
    public function delete()
    {
        // If deposit is existing, will get database ID removed.
        $this->dbID = $this->db->deleteDeposit($this);
    }

    /**
     * Returns the database ID for this deposit.
     *
     * @return int
     */
    public function getDBID()
    {
        return $this->dbID;
    }

    /**
     * Returns the size for this deposit.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns the x coordinate for this deposit.
     *
     * @return int
     */
    public function getLocationX()
    {
        return $this->locationX;
    }

    /**
     * Returns the Y coordinate for this deposit.
     *
     * @return int
     */
    public function getLocationY()
    {
        return $this->locationY;
    }

    /**
     * Returns the planet this deposit is on.
     *
     * @return Planet
     */
    public function getPlanet()
    {
        return $this->planet;
    }

    /**
     * Returns the type of this deposit.
     *
     * @return int
     */
    public function getType()
    {
        return $this->depositType;
    }

    /*public function setSize($newSize)
    {
        $this->size = $newSize;
    }

    public function setType($newType)
    {
        $this->depositType = $newType;
    }*/
}
