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

        print '
            <style>
                .planet-tile
                {
                    float:left;
                    max-width:15em;
                    width: 15em;
                    margin:1em;
                }
            </style>
        ';
    }

    public function startBody($title = 'Welcome to SWCProspect')
    {
        parent::startBody();

        print "
        <div data-role='page' data-theme='b' id='main-page'>
            <div data-role='header' data-position='inline'>
                    <h1>$title</h1>
            </div>
        ";

        if (!$this->dbInteractor->isAvailable()) {
            print '
                <p><b>Could not connect to database! Unable to continue.</b></p>
            ';

            return;
        }

        $this->planetTiles();
    }

    private function planetTiles()
    {
        $planets = $this->dbInteractor->getPlanets();
        if (!is_array($planets)) {
            print '
                <p><b>Could fetch planets! Unable to continue.</b></p>
            ';
        }

        foreach ($planets as $planet) {
            $name = $planet->getName();
            $showSize = 5 + (($planet->getSize() - 1) * (40 / 19));
            $colour = $planet->getType()->getHTMLColour();
            $numDep = $this->dbInteractor->getNumDeposits($planet->getDBID());

            print "
            <a href='#'>
                <div class='ui-corner-all planet-tile'>
                    <div class='ui-bar ui-bar-b'>
                        <h3>$name</h3>
                    </div>
                    <div class='ui-body ui-body-b'>
                        <svg width='100%' height='15em'>
                            <circle cx='50%' cy='50%' r='$showSize%' fill='$colour'/>
                            <text x='44%' y='45%' fill='#FFFFFF' font-size='3em'>$numDep</text>
                            <text x='19%' y='60%' fill='#B0B0B0' font-size='2em'>Deposits</text>
                        </svg>
                    </div>
                </div>
            </a>
            ";
        }
    }

    public function endBody()
    {
        print '
        </div>
        ';

        parent::endBody();
    }
}
