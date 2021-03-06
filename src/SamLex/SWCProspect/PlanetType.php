<?php

/** Part of SWCProspect, contains PlanetType class. */
namespace SamLex\SWCProspect;

use SamLex\SWCProspect\Database\DatabaseInteractor;

/** Data class that holds data on a planet type. */
class PlanetType
{
    /**
     * The database ID of the planet type.
     *
     * @var int
     */
    private $dbID;

    /**
     * The description of the planet type.
     *
     * @var string
     */
    private $description;

    /**
     * The HTML colour to be used to represent this planet type.
     *
     * @var string
     */
    private $htmlColour;

    /**
     * Constructs a new PlanetType instance.
     *
     * @param int    $dbID        The database ID of the planet type.
     * @param string $description The description of the planet type.
     * @param string $htmlColour  The HTML colour to be used to represent this planet type.
     */
    public function __construct($dbID, $description, $htmlColour)
    {
        $this->dbID = $dbID;
        $this->description = $description;
        $this->htmlColour = $htmlColour;
    }

    /**
     * Returns the database ID for this type.
     *
     * @return int
     */
    public function getDBID()
    {
        return $this->dbID;
    }

    /**
     * Returns the description for this type.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the HTML colour for this type.
     *
     * @return string
     */
    public function getHTMLColour()
    {
        return $this->htmlColour;
    }

    /**
     * Returns an array of all planet types.
     *
     * @param DatabaseInteractor $db The database to get types from.
     *
     * @return PlanetType[]
     */
    public static function getTypes($db)
    {
        return $db->getPlanetTypes();
    }

    /**
     * Returns the type with the given id.
     *
     * @param DatabaseInteractor $db     The database to get type from.
     * @param int                $typeID
     *
     * @return PlanetType
     */
    public static function getType($db, $typeID)
    {
        return $db->getPlanetType($typeID);
    }
}
