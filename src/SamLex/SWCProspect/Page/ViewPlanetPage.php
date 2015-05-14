<?php

namespace SamLex\SWCProspect\Page;

class ViewPlanetPage extends Page
{
    private $dbInteractor;
    private $planet;

    public function __construct($dbInteractor, $planetID)
    {
        $this->dbInteractor = $dbInteractor;
        $this->planet = $this->dbInteractor->getPlanet($planetID);
    }

    public function startBody()
    {
        parent::startBody();

        printf("
        <div data-role='page' data-theme='b' id='view-planet-%s'>
            <div data-role='header' data-position='inline'>
                <a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>
                <h1>%s</h1>
            </div>
        ", $this->planet->getName(), $this->planet->getName());
    }

    public function endBody()
    {
        printf('
        </div>
        ');

        parent::endBody();
    }
}
