<?php

namespace SamLex\SWCProspect\Page;

/*
    The edit planet page
*/
class EditPlanetPage extends Page
{
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

    public function __construct($db, $planetID)
    {
        $dbError = !$db->isAvailable();

        if ($dbError === false) {
            $planetTypes = $db->getPlanetTypes();

            if ($planetTypes === false) {
                $dbError = true;
            } else {
                $planet = $db->getPlanet($planetID);

                if ($planet === false) {
                    $dbError = true;
                }
            }
        }

        $this->addToJQHeaderBeforeTitle("<a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>");

        if ($dbError === true) {
            $this->setTitle('Error');
            $this->setJQPageID('swcprospect-edit-planet-page-error');
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        } else {
            $this->setJQPageID(sprintf('swcprospect-edit-planet-page-%s%d', $planet->getName(), $planet->getDBID()));
            $this->setTitle(sprintf('Edit %s', $planet->getName()));
            $this->addToJQContent($this->editPlanetForm($planetTypes, $planet));
        }
    }

    private function editPlanetForm($types, $planet)
    {
        $form = $this->editPlanetFormTemplate;

        $form = str_replace('%%PLANET_ID%%', $planet->getDBID(), $form);
        $form = str_replace('%%PLANET_NAME%%', $planet->getName(), $form);
        $form = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', $planet->getName(), $planet->getDBID()), $form);
        $form = str_replace('%%SIZE_OPTIONS%%', $this->genSizeOptions(1, 20, $planet->getSize()), $form);
        $form = str_replace('%%TYPE_OPTIONS%%', $this->genTypeOptions($types, $planet->getType()), $form);

        return $form;
    }

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
