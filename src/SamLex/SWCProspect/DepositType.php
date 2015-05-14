<?php

namespace SamLex\SWCProspect;

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
