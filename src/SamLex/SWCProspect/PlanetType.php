<?php

namespace SamLex\SWCProspect;

/*
    Data class that holds data on a planet type
*/
class PlanetType
{
    private $dbID;
    private $description;
    private $htmlColour;

    public function __construct($dbID, $description, $htmlColour)
    {
        $this->dbID = $dbID;
        $this->description = $description;
        $this->htmlColour = $htmlColour;
    }

    public function getDBID()
    {
        return $this->dbID;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getHTMLColour()
    {
        return $this->htmlColour;
    }
}
