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
}
