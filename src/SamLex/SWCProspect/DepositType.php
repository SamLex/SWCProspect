<?php

/** Part of SWCProspect, contains DepositType class. */
namespace SamLex\SWCProspect;

/** Data class that holds data on a deposit type. */
class DepositType
{
    /**
     * The database ID of the deposit type.
     *
     * @var int
     */
    private $dbID;

    /**
     * The material name of the deposit type.
     *
     * @var string
     */
    private $material;

    /**
     * The HTML colour to be used to represent this deposit type.
     *
     * @var string
     */
    private $htmlColour;

    /**
     * Constructs a new DepositType instance.
     *
     * @param int    $dbID       The database ID of the deposit type.
     * @param string $material   The material name of the deposit type.
     * @param string $htmlColour The HTML colour to be used to represent this deposit type.
     */
    public function __construct($dbID, $material, $htmlColour)
    {
        $this->dbID = $dbID;
        $this->material = $material;
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
     * Returns the material name for this type.
     *
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
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
     * Returns an array of all deposit types.
     *
     * @param DatabaseInteractor $db The database to get types from.
     *
     * @return DepositType[]
     */
    public static function getTypes($db)
    {
        return $db->getDepositTypes();
    }

    /**
     * Returns the type with the given id.
     *
     * @param DatabaseInteractor $db     The database to get type from.
     * @param int                $typeID
     *
     * @return DepositType
     */
    public static function getType($db, $typeID)
    {
        return $db->getDepositType($typeID);
    }
}
