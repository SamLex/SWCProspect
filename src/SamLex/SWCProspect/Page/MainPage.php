<?php

namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Planet;

class MainPage extends Page
{
    private $dbInteractor;

    public function __construct($dbInteractor)
    {
        $this->dbInteractor = $dbInteractor;
    }

    public function startHead($title)
    {
        parent::startHead($title);

        printf('
            <style>
                .planet-tile
                {
                    float:left;
                    max-width:15em;
                    width: 15em;
                    margin:1em;
                    text-align: center;
                }
            </style>
        ');
    }

    public function startBody($title = 'Welcome to SWCProspect')
    {
        parent::startBody();

        printf("
        <div data-role='page' data-theme='b' id='main-page'>
            <div data-role='header' data-position='inline'>
                    <h1>%s</h1>
            </div>
        ", $title);

        if (!$this->dbInteractor->isAvailable()) {
            printf('
                <p><b>Could not connect to database! Unable to continue.</b></p>
            ');

            return;
        }

        $this->planetTiles();
        $this->newPlanetTile();
    }

    public function endBody()
    {
        printf('
        </div>
        ');

        parent::endBody();
    }

    private function planetTiles()
    {
        $planets = $this->dbInteractor->getPlanets();
        if (!is_array($planets)) {
            printf('
                <p><b>Could fetch planets! Unable to continue.</b></p>
            ');
        }

        foreach ($planets as $planet) {
            $showSize = 5 + (($planet->getSize() - 1) * (40 / 19));

            printf("
            <a href='viewplanet.php?planetid=%d'>
                <div class='ui-corner-all planet-tile'>
                    <div class='ui-bar ui-bar-b'>
                        <h3>%s</h3>
                    </div>
                    <div class='ui-body ui-body-b'>
                        <svg width='100%%' height='15em'>
                            <circle cx='50%%' cy='50%%' r='%d%%' fill='%s'/>
                            <text x='44%%' y='45%%' fill='#FFFFFF' font-size='3em'>%d</text>
                            <text x='19%%' y='60%%' fill='#B0B0B0' font-size='2em'>Deposits</text>
                        </svg>
                    </div>
                </div>
            </a>
            ", $planet->getDBID(), $planet->getName(), $showSize, $planet->getType()->getHTMLColour(), $this->dbInteractor->getNumDeposits($planet->getDBID()));
        }
    }

    private function newPlanetTile()
    {
        printf("
        <a href='addplanet.php'>
            <div class='ui-corner-all planet-tile'>
                <div class='ui-bar ui-bar-b'>
                    <h3></h3>
                </div>
                <div class='ui-body ui-body-b'>
                    <svg width='100%%' height='15em'>
                        <rect x='31%%' y='45%%' width='35%%' height='7.5%%' fill='#707070' fill-opacity='0.85' />
                        <rect x='45%%' y='31%%' width='8%%' height='35%%' fill='#707070' fill-opacity='0.85' />
                        <text x='33%%' y='30%%' fill='#B0B0B0' font-size='2em'>Add</text>
                        <text x='33%%' y='43%%' fill='#B0B0B0' font-size='2em'>New</text>
                        <text x='26%%' y='56%%' fill='#B0B0B0' font-size='2em'>Planet</text>
                    </svg>
                </div>
            </div>
        </a>
        ");
    }
}
