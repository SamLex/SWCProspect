<?php

namespace SamLex\SWCProspect;

/*
    Data class that holds data on a planet
*/
class Planet
{
    private $dbID;
    private $name;
    private $size;
    private $planetType;

    private $db;

    public function __construct($name, $size, $planetType, $db, $id = -1)
    {
        $this->dbID = $id;
        $this->name = $name;
        $this->size = $size;
        $this->planetType = $planetType;

        $this->db = $db;
    }

    public function save()
    {
        $db->savePlanet($this);
    }

    public function delete()
    {
        $db->deletePlanet($this);
    }

    public function getDBID()
    {
        return $this->dbID;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getType()
    {
        return $this->planetType;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setSize($newSize)
    {
        $this->size = $newSize;
    }

    public function setType($newType)
    {
        $this->planetType = $newTypes;
    }
}
