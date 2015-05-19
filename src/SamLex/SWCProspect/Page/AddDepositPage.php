<?php

namespace SamLex\SWCProspect\Page;

/*
    The add deposit page
*/
class AddDepositPage extends Page
{
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

    public function __construct($db, $planetID)
    {
        $dbError = !$db->isAvailable();

        if ($dbError === false) {
            $depositTypes = $db->getDepositTypes();

            if ($depositTypes === false) {
                $dbError = true;
            } else {
                $planet = $db->getPlanet($planetID);

                if ($planet === false) {
                    $dbError = true;
                }
            }
        }

        $this->setJQPageID('swcprospect-add-deposit-page');
        $this->setTitle('Add New Deposit');
        $this->addToJQHeaderBeforeTitle("<a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>");

        if ($dbError === true) {
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        } else {
            $this->addToJQContent($this->addDepositForm($depositTypes, $planet));
        }
    }

    private function addDepositForm($depositTypes, $planet)
    {
        $form = $this->addDepositFormTemplate;

        $form = str_replace('%%PLANET_ID%%', $planet->getDBID(), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($depositTypes), $form);
        $form = str_replace('%%LOCX_OPTIONS%%', $this->genLocOptions($planet->getSize()), $form);
        $form = str_replace('%%LOCY_OPTIONS%%', $this->genLocOptions($planet->getSize()), $form);

        return $form;
    }

    private function genTypeOptions($types)
    {
        $options = '';

        foreach ($types as $type) {
            $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getMaterial());
        }

        return $options;
    }

    private function genLocOptions($size)
    {
        $options = '';

        for ($loc = 0;$loc < $size;$loc++) {
            $options = $options."<option value='$loc'>$loc</option>";
        }

        return $options;
    }
}
