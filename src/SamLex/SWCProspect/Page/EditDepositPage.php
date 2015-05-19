<?php

namespace SamLex\SWCProspect\Page;

/*
    The edit deposit page
*/
class EditDepositPage extends Page
{
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

    public function __construct($db, $depositID)
    {
        $dbError = !$db->isAvailable();

        if ($dbError === false) {
            $depositTypes = $db->getDepositTypes();

            if ($depositTypes === false) {
                $dbError = true;
            } else {
                $deposit = $db->getDeposit($depositID);

                if ($deposit === false) {
                    $dbError = true;
                }
            }
        }

        $this->setTitle('Edit Deposit');
        $this->addToJQHeaderBeforeTitle("<a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>");

        if ($dbError === true) {
            $this->setJQPageID('swcprospect-edit-deposit-page-error');
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        } else {
            $this->setJQPageID(sprintf('swcprospect-add-deposit-page-%d', $deposit->getDBID()));
            $this->addToJQContent($this->editDepositForm($depositTypes, $deposit));
        }
    }

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
