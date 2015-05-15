<?php

namespace SamLex\SWCProspect\Page;

/*
    The add planet page
*/
class AddPlanetPage extends Page
{
    private $dbInteractor;

    public function __construct($dbInteractor)
    {
        $this->dbInteractor = $dbInteractor;
    }

    public function startBody()
    {
        parent::startBody();

        printf("
        <div data-role='page' data-theme='b' id='add-planet'>
            <div data-role='header' data-position='inline'>
                <a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>
                <h1>Add New Planet</h1>
            </div>
            <div data-role='content'>
                <form method='post' action='addplanetworker.php' data-ajax='false'>
                    <label for='add-planet-name'>Name</label>
                    <input type='text' name='name' maxlength='254' id='add-planet-name'>
                    <label for='add-planet-size' class='select'>Size</label>
                    <select name='size' id='add-planet-size'>
        ");

        for ($i = 1;$i <= 30;$i++) {
            printf("
                        <option value='%1\$d'>%1\$dx%1\$d</option>
            ", $i);
        }

        printf("
                    </select>
                    <label for='add-planet-type' class='select'>Planet Type</label>
                    <select name='type' id='add-planet-type'>
        ");

        $planetTypes = $this->dbInteractor->getPlanetTypes();

        if (!$planetTypes) {
            printf('
                    </select>
            ');
        } else {
            foreach ($planetTypes as $type) {
                printf("
                        <option value='%d'>%s</option>
                ", $type->getDBID(), $type->getDescription());
            }

            printf('
                    </select>
            ');
        }

        if (!$planetTypes) {
            printf("
                <button type='submit' disabled=''>Add Planet</button>
            ");
        } else {
            printf("
                <button type='submit'>Add Planet</button>
            ");
        }

        printf('
                </form>
        ');
    }

    public function endBody()
    {
        printf('
            </div>
        </div>
        ');

        parent::endBody();
    }
}
