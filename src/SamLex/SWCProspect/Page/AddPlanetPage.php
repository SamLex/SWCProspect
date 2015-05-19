<?php

namespace SamLex\SWCProspect\Page;

/*
    The add planet page
*/
class AddPlanetPage extends Page
{
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

    public function __construct($db)
    {
        $dbError = !$db->isAvailable();

        if ($dbError === false) {
            $planetTypes = $db->getPlanetTypes();

            if ($planetTypes === false) {
                $dbError = true;
            }
        }

        $this->setJQPageID('swcprospect-add-planet-page');
        $this->setTitle('Add New Planet');
        $this->addToJQHeaderBeforeTitle("<a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>");

        if ($dbError === true) {
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        } else {
            $this->addToJQContent($this->addPlanetForm($planetTypes));
        }
    }

    private function addPlanetForm($types)
    {
        $form = $this->addPlanetFormTemplate;

        $form = str_replace('%%SIZE_OPTIONS%%', $this->genSizeOptions(1, 20), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($types), $form);

        return $form;
    }

    private function genSizeOptions($min, $max)
    {
        $options = '';

        for ($size = $min;$size <= $max;$size++) {
            $options = $options."<option value='$size'>{$size}x{$size}</option>";
        }

        return $options;
    }

    private function genTypeOptions($types)
    {
        $options = '';

        foreach ($types as $type) {
            $options = $options.sprintf("<option value='%d'>%s</option>", $type->getDBID(), $type->getDescription());
        }

        return $options;
    }
}
