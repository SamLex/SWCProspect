<?php

/** Part of SWCProspect, contains AddDepositPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\DepositType;

/**
 * The add deposit page.
 *
 * Shows a form with all nessessary fields to create a new deposit.
 */
class AddDepositPage extends Page
{
    /** The template for the form. */
    private $addDepositFormTemplate = "
    <form method='post' action='worker/adddepositworker.php' data-ajax='false'>
        <input type='hidden' name='planetid' value='%%PLANET_ID%%'>
        <label for='swcprospect-add-deposit-page-type' class='select'>Material</label>
        <select name='type' id='swcprospect-add-deposit-page-type'>
            %%TYPE_OPTIONS%%
        </select>
        <label for='swcprospect-add-deposit-page-size'>Size</label>
        <input type='number' name='size' id='swcprospect-add-deposit-page-size'>
        <label for='swcprospect-add-deposit-page-locx' class='select'>Location X</label>
        <select name='locx' id='swcprospect-add-deposit-page-locx'>
            %%LOCX_OPTIONS%%
        </select>
        <label for='swcprospect-add-deposit-page-locy' class='select'>Location Y</label>
        <select name='locy' id='swcprospect-add-deposit-page-locy'>
            %%LOCY_OPTIONS%%
        </select>
        <button type='submit'>Add Deposit</button
    </form>
    ";

    /** The DatabaseInteractor instance to use to get data. */
    private $db = null;

    /** The planet id of the planet the new deposit is on. */
    private $planetID = -1;

    /**
     * Constructs a new AddDepositPage instance.
     *
     * @param DatabaseInteractor $db       The DatabaseInteractor instance to use to get data.
     * @param int                $planetID The planet id of the planet the new deposit is on.
     */
    public function __construct($db, $planetID)
    {
        $this->db = $db;
        $this->planetID = $planetID;
        $this->setJQPageID('swcprospect-add-deposit-page');
        $this->setTitle('Add New Deposit');
        $this->addToJQHeaderBeforeTitle($this->backButtonTemplate);
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $depositTypes = DepositType::getTypes();
        $planet = Planet::getPlanet();

        if (!is_null($planet) && (count($depositTypes) !== 0)) {
            $this->addToJQContent($this->addDepositForm($depositTypes, $planet));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the form.
     *
     * @param DepositType[] $depositTypes An array of all known deposit types.
     * @param Planet        $planet       The planet the new deposit is on.
     *
     * @return string The generated form.
     */
    private function addDepositForm($depositTypes, $planet)
    {
        $form = $this->addDepositFormTemplate;

        $form = str_replace('%%PLANET_ID%%', $planet->getDBID(), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($depositTypes), $form);
        $form = str_replace('%%LOCX_OPTIONS%%', $this->genLocOptions($planet->getSize()), $form);
        $form = str_replace('%%LOCY_OPTIONS%%', $this->genLocOptions($planet->getSize()), $form);

        return $form;
    }

    /**
     * Generates the type options for a select element in the form.
     *
     * @param DepositType[] $types An array of all known deposit types.
     *
     * @return string The generated options.
     */
    private function genTypeOptions($types)
    {
        $options = '';

        foreach ($types as $type) {
            $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getMaterial());
        }

        return $options;
    }

    /**
     * Generates the location options for a select element in the form.
     *
     * @param int $size The max location option.
     *
     * @return string The generated options.
     */
    private function genLocOptions($size)
    {
        $options = '';

        for ($loc = 0;$loc < $size;$loc++) {
            $options = $options."<option value='$loc'>$loc</option>";
        }

        return $options;
    }
}
