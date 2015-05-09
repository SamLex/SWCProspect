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
}
