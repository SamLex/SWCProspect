<?php

/** Part of SWCProspect, contains EditDepositPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\DepositType;
use SamLex\SWCProspect\Deposit;

/**
 * The edit deposit page.
 *
 * Shows a form with all nessessary fields to edit an existing deposit.
 */
class EditDepositPage extends Page
{
    /** The template for the form. */
    private $editDepositFormTemplate = "
    <form method='post' action='worker/editdepositworker.php' data-ajax='false'>
        <input type='hidden' name='planetid' value='%%PLANET_ID%%'>
        <input type='hidden' name='depositid' value='%%DEPOSIT_ID%%'>
        <label for='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-type' class='select'>Material</label>
        <select name='type' id='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-type'>
            %%TYPE_OPTIONS%%
        </select>
        <label for='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-size'>Size</label>
        <input type='number' name='size' id='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-size' value='%%DEPOSIT_SIZE%%'>
        <label for='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-locx' class='select'>Location X</label>
        <select name='locx' id='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-locx'>
            %%LOCX_OPTIONS%%
        </select>
        <label for='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-locy' class='select'>Location Y</label>
        <select name='locy' id='swcprospect-edit-deposit-page-%%DEPOSIT_ID%%-locy'>
            %%LOCY_OPTIONS%%
        </select>
        <button type='submit'>Update</button
    </form>
    ";

    /**
     * The DatabaseInteractor instance to use to get data.
     *
     * @var DatabaseInteractor
     */
    private $db = null;

    /**
     * The deposit id of the deposit getting edited.
     *
     * @var int
     */
    private $depositID = -1;

    /**
     * Constructs a new EditDepositPage instance.
     *
     * @param DatabaseInteractor $db        The DatabaseInteractor instance to use to get data.
     * @param int                $depositID The deposit id of the deposit getting edited.
     */
    public function __construct($db, $depositID)
    {
        $this->db = $db;
        $this->depositID = $depositID;
        $this->setTitle('Edit Deposit');
        $this->addToJQHeaderBeforeTitle($this->backButtonTemplate);
        $this->setJQPageID(sprintf('swcprospect-add-deposit-page-%d', $depositID));
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $depositTypes = DepositType::getTypes($this->db);
        $deposit = Deposit::getDeposit($this->db, $this->depositID);

        if (!is_null($deposit) && (count($depositTypes) !== 0)) {
            $this->addToJQContent($this->editDepositForm($depositTypes, $deposit));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the form.
     *
     * @param DepositType[] $depositTypes An array of all known deposit types.
     * @param Deposit       $deposit      The deposit.
     *
     * @return string The generated form.
     */
    private function editDepositForm($depositTypes, $deposit)
    {
        $form = $this->editDepositFormTemplate;

        $form = str_replace('%%PLANET_ID%%', $deposit->getPlanet()->getDBID(), $form);
        $form = str_replace('%%DEPOSIT_ID%%', $deposit->getDBID(), $form);
        $form = str_replace('%%DEPOSIT_SIZE%%', $deposit->getSize(), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($depositTypes, $deposit->getType()), $form);
        $form = str_replace('%%LOCX_OPTIONS%%', $this->genLocOptions($deposit->getPlanet()->getSize(), $deposit->getLocationX()), $form);
        $form = str_replace('%%LOCY_OPTIONS%%', $this->genLocOptions($deposit->getPlanet()->getSize(), $deposit->getLocationY()), $form);

        return $form;
    }

    /**
     * Generates the type options for a select element in the form.
     *
     * @param DepositType[] $types    An array of all known deposit types.
     * @param DepositType   $existing The current deposit type.
     *
     * @return string The generated options.
     */
    private function genTypeOptions($types, $existing)
    {
        $options = '';

        foreach ($types as $type) {
            if ($type->getDBID() == $existing->getDBID()) {
                $options = $options.sprintf("<option value='%d' selected>%s</option>", $type->getDBID(), $type->getMaterial());
            } else {
                $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getMaterial());
            }
        }

        return $options;
    }

    /**
     * Generates the location options for a select element in the form.
     *
     * @param int $size     The max location option.
     * @param int $existing The current location.
     *
     * @return string The generated options.
     */
    private function genLocOptions($size, $existing)
    {
        $options = '';

        for ($loc = 0;$loc < $size;$loc++) {
            if ($loc == $existing) {
                $options = $options."<option value='$loc' selected>$loc</option>";
            } else {
                $options = $options."<option value='$loc'>$loc</option>";
            }
        }

        return $options;
    }
}
