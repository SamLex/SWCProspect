<?php

namespace SamLex\SWCProspect;

/*
    Data class that holds data on a deposit
*/
class Deposit
{
    private $dbID;
    private $size;
    private $locationX;
    private $locationY;
    private $planet;
    private $depositType;

    private $db;

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

    public function save()
    {
        $db->saveDeposit($this);
    }

    public function delete()
    {
        $db->deleteDeposit($this);
    }

    public function getDBID()
    {
        return $this->dbID;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getLocationX()
    {
        return $this->locationX;
    }

    public function getLocationY()
    {
        return $this->locationY;
    }

    public function getPlanet()
    {
        return $this->planet;
    }

    public function getType()
    {
        return $this->depositType;
    }

    public function setSize($newSize)
    {
        $this->size = $newSize;
    }

    public function setType($newType)
    {
        $this->depositType = $newType;
    }
}
