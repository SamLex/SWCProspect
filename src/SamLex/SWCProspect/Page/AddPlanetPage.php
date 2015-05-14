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
        ");
    }

    public function endBody()
    {
        printf('
        </div>
        ');

        parent::endBody();
    }
}
