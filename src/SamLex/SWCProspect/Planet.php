<?php

namespace SamLex\SWCProspect;

class Planet
{
    private $dbID;
    private $name;
    private $size;

    private $source;

    public function __construct($src, $name, $size, $id = -1)
    {
        $this->source = $src;
        $this->name = $name;
        $this->size = $size;
        $this->dbID = $id;
    }
    
    public function save()
    {
        $source->savePlanet($this);
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
    
    public function setName($newName)
    {
        $this->name = $newName;
    }
    
    public function setSize($newSize)
    {
        $this->size = $newSize;
    }
}
