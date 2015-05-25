<?php

/** Part of SWCProspect, contains EditPlanetPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\PlanetType;
use SamLex\SWCProspect\Planet;

/**
 * The edit planet page.
 *
 * Shows a form with all nessessary fields to edit an exisiting planet.
 */
class EditPlanetPage extends Page
{
    /** The template for the form. */
    private $editPlanetFormTemplate = "
    <form method='post' action='worker/editplanetworker.php' data-ajax='false'>
        <input type='hidden' name='planetid' value='%%PLANET_ID%%'>
        <label for='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-name'>Name</label>
        <input type='text' name='name' maxlength='254' id='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-name' value='%%PLANET_NAME%%'>
        <label for='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-size' class='select'>Size</label>
        <select name='size' id='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-size'>
            %%SIZE_OPTIONS%%
        </select>
        <label for='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-type' class='select'>Planet Type</label>
        <select name='type' id='swcprospect-edit-planet-page-%%PLANET_NAMEID%%-type'>
            %%TYPE_OPTIONS%%
        </select>
        <button type='submit'>Update Planet</button
    </form>
    ";

    /** The DatabaseInteractor instance to use to get data. */
    private $db = null;

    /** The planet id of the planet being edited. */
    private $planetID = -1;

    /**
     * Constructs a new EditPlanetPage instance.
     *
     * @param DatabaseInteractor $db       The DatabaseInteractor instance to use to get data.
     * @param int                $planetID The planet id of the planet being edited.
     */
    public function __construct($db, $planetID)
    {
        $this->db = $db;
        $this->planetID = $planetID;
        $this->addToJQHeaderBeforeTitle($this->backButtonTemplate);
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $planetTypes = PlanetType::getTypes($this->db);
        $planet = Planet::getPlanet($this->db, $this->planetID);

        if (!is_null($planet) && (count($planetTypes) !== 0)) {
            $spacelessName = str_replace(' ', '', $planet->getName());

            $this->setJQPageID(sprintf('swcprospect-edit-planet-page-%s%d', $spacelessName, $planet->getDBID()));
            $this->setTitle(sprintf('Edit %s', $planet->getName()));
            $this->addToJQContent($this->editPlanetForm($planetTypes, $planet, $spacelessName));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the form.
     *
     * @param PlanetType[] $types         An array of all known planet types.
     * @param Planet       $planet        The planet.
     * @param string       $spacelessName The name of the planet stripped of spaces.
     *
     * @return string The generated form.
     */
    private function editPlanetForm($types, $planet, $spacelessName)
    {
        $form = $this->editPlanetFormTemplate;

        $form = str_replace('%%PLANET_ID%%', $planet->getDBID(), $form);
        $form = str_replace('%%PLANET_NAME%%', $planet->getName(), $form);
        $form = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', $spacelessName, $planet->getDBID()), $form);
        $form = str_replace('%%SIZE_OPTIONS%%', $this->genSizeOptions(1, 20, $planet->getSize()), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($types, $planet->getType()), $form);

        return $form;
    }

    /**
     * Generates the size options for a select element in the form.
     *
     * @param int $min      The min size option.
     * @param int $max      The max size option.
     * @param int $existing The current size.
     *
     * @return string The generated options.
     */
    private function genSizeOptions($min, $max, $existing)
    {
        $options = '';

        for ($size = $min;$size <= $max;$size++) {
            if ($size == $existing) {
                $options = $options."<option value='$size' selected>{$size}x{$size}</option>";
            } else {
                $options = $options."<option value='$size'>{$size}x{$size}</option>";
            }
        }

        return $options;
    }

    /**
     * Generates the type options for a select element in the form.
     *
     * @param PlanetType[] $types    An array of all known planet types.
     * @param PlanetType   $existing The current type.
     *
     * @return string The generated options.
     */
    private function genTypeOptions($types, $existing)
    {
        $options = '';

        foreach ($types as $type) {
            if ($type->getDBID() === $existing->getDBID()) {
                $options = $options.sprintf("<option value='%d' selected>%s</option>", $type->getDBID(), $type->getDescription());
            } else {
                $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getDescription());
            }
        }

        return $options;
    }
}
