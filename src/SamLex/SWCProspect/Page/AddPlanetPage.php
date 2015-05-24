<?php

/** Part of SWCProspect, contains AddPlanetPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\PlanetType;

/**
 * The add planet page.
 *
 * Shows a form with all nessessary fields to create a new planet.
 */
class AddPlanetPage extends Page
{
    /** The template for the form. */
    private $addPlanetFormTemplate = "
    <form method='post' action='worker/addplanetworker.php' data-ajax='false'>
        <label for='swcprospect-add-planet-page-name'>Name</label>
        <input type='text' name='name' maxlength='254' id='swcprospect-add-planet-page-name'>
        <label for='swcprospect-add-planet-page-size' class='select'>Size</label>
        <select name='size' id='swcprospect-add-planet-page-size'>
            %%SIZE_OPTIONS%%
        </select>
        <label for='swcprospect-add-planet-page-type' class='select'>Planet Type</label>
        <select name='type' id='swcprospect-add-planet-page-type'>
            %%TYPE_OPTIONS%%
        </select>
        <button type='submit'>Add Planet</button
    </form>
    ";

    /** The DatabaseInteractor instance to use to get data. */
    private $db = null;

    /**
     * Constructs a new AddDepositPage instance.
     *
     * @param DatabaseInteractor $db The DatabaseInteractor instance to use to get data.
     */
    public function __construct($db)
    {
        $this->db = $db;
        $this->setJQPageID('swcprospect-add-planet-page');
        $this->setTitle('Add New Planet');
        $this->addToJQHeaderBeforeTitle($this->backButtonTemplate);
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $planetTypes = PlanetType::getTypes();

        if (count($planetTypes) !== 0) {
            $this->addToJQContent($this->addPlanetForm($planetTypes));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the form.
     *
     * @param PlanetType[] $types An array of all known planet types.
     *
     * @return string The generated form.
     */
    private function addPlanetForm($types)
    {
        $form = $this->addPlanetFormTemplate;

        $form = str_replace('%%SIZE_OPTIONS%%', $this->genSizeOptions(1, 20), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($types), $form);

        return $form;
    }

    /**
     * Generates the size options for a select element in the form.
     *
     * @param int $min The min size option.
     * @param int $max The max size option.
     *
     * @return string The generated options.
     */
    private function genSizeOptions($min, $max)
    {
        $options = '';

        for ($size = $min;$size <= $max;$size++) {
            $options = $options."<option value='$size'>{$size}x{$size}</option>";
        }

        return $options;
    }

    /**
     * Generates the type options for a select element in the form.
     *
     * @param PlanetType[] $types An array of all known planet types.
     *
     * @return string The generated options.
     */
    private function genTypeOptions($types)
    {
        $options = '';

        foreach ($types as $type) {
            $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getDescription());
        }

        return $options;
    }
}
