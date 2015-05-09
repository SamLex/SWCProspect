<?php

namespace SamLex\SWCProspect;

class Result
{
    private $dbID;
    private $planetID;
    private $material;
    private $size;
    private $locationX;
    private $locationY;

    private $source;

    public function __construct($src, $planetID, $material, $size, $locX, $locY, $id = -1)
    {
        $this->source = $src;
        $this->planetID = $planetID;
        $this->material = $material;
        $this->size = $size;
        $this->locationX = $locX;
        $this->locationY = $locY;
        $this->dbID = $id;
    }

    public function save()
    {
        $source->saveResult($this);
    }

    public function getDBID()
    {
        return $this->dbID;
    }

    public function getPlanetID()
    {
        return $this->planetID;
    }

    public function getMaterial()
    {
        return $this->material;
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

    public function setMaterial($newMat)
    {
        $this->material = $newMat;
    }

    public function setSize($newSize)
    {
        $this->size = $newSize;
    }

    public function setLocationX($newLocX)
    {
        $this->locationX = $newLocX;
    }

    public function setLocationY($newLocY)
    {
        $this->locationY = $newLocY;
    }
}
