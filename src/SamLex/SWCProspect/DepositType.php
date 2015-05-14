<?php

namespace SamLex\SWCProspect;

/*
    Data class that holds data on a deposit type
*/
class DepositType
{
    private $dbID;
    private $material;
    private $htmlColour;

    public function __construct($dbID, $material, $htmlColour)
    {
        $this->dbID = $dbID;
        $this->material = $material;
        $this->htmlColour = $htmlColour;
    }

    public function getDBID()
    {
        return $this->dbID;
    }

    public function getMaterial()
    {
        return $this->material;
    }

    public function getHTMLColour()
    {
        return $this->htmlColour;
    }
}
