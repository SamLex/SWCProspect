<?php

/** Part of SWCProspect, contains MainPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\Planet;

/**
 * The main page.
 *
 * Shows a tile for each planet and an add new planet tile.
 */
class MainPage extends Page
{
    /** The template for the planet tiles. */
    private $planetTileTemplate = "
    <a href='viewplanet.php?planetid=%%PLANET_ID%%'>
        <div class='ui-corner-all main-page-planet-tile'>
            <div class='ui-bar ui-bar-b'>
                <h3>%%PLANET_NAME%%</h3>
            </div>
            <div class='ui-body ui-body-b'>
                <svg width='100%' height='15em'>
                    <circle cx='50%' cy='50%' r='%%PLANET_SHOW_SIZE%%%' fill='%%PLANET_COLOUR%%'/>
                    <text x='44%' y='45%' fill='#FFFFFF' font-size='3em'>%%PLANET_NUM_DEPOSITS%%</text>
                    <text x='19%' y='60%' fill='#B0B0B0' font-size='2em'>Deposits</text>
                </svg>
            </div>
        </div>
    </a>
    ";

    /** The template for the add new planet tile. */
    private $newPlanetTileTemplate = "
    <a href='addplanet.php'>
        <div class='ui-corner-all main-page-planet-tile'>
            <div class='ui-bar ui-bar-b'>
                <h3></h3>
            </div>
            <div class='ui-body ui-body-b'>
                <svg width='100%' height='15em'>
                    <rect x='31%' y='45%' width='35%' height='7.5%' fill='#707070' fill-opacity='0.85' />
                    <rect x='45%' y='31%' width='8%' height='35%' fill='#707070' fill-opacity='0.85' />
                    <text x='33%' y='30%' fill='#B0B0B0' font-size='2em'>Add</text>
                    <text x='33%' y='43%' fill='#B0B0B0' font-size='2em'>New</text>
                    <text x='26%' y='56%' fill='#B0B0B0' font-size='2em'>Planet</text>
                </svg>
            </div>
        </div>
    </a>
    ";

    /** The DatabaseInteractor instance to use to get data. */
    private $db = null;

    /**
     * Constructs a new MainPage instance.
     *
     * @param DatabaseInteractor $db The DatabaseInteractor instance to use to get data.
     */
    public function __construct($db)
    {
        $this->db = db;
        $this->setJQPageID('swcprospect-main-page');
        $this->setTitle('Welcome to SWCProspect');
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $planets = Planet::getPlanets();

        foreach ($planets as $planet) {
            $this->addToJQContent($this->planetTile($planet));
        }

        $this->addToJQContent($this->newPlanetTile());
        
        return true;
    }

    /**
     * Generates a planet tile.
     *
     * @param Planet $planet The planet to generate the tile for.
     *
     * @return string The generate planet tile
     */
    private function planetTile($planet)
    {
        $numDeposits = $planet->getNumDeposits();
        $showSize = 5 + (($planet->getSize() - 1) * (40 / 19));

        $tile = $this->planetTileTemplate;

        $tile = str_replace('%%PLANET_ID%%', $planet->getDBID(), $tile);
        $tile = str_replace('%%PLANET_NAME%%', $planet->getName(), $tile);
        $tile = str_replace('%%PLANET_SHOW_SIZE%%', $showSize, $tile);
        $tile = str_replace('%%PLANET_COLOUR%%', $planet->getType()->getHTMLColour(), $tile);
        $tile = str_replace('%%PLANET_NUM_DEPOSITS%%', $numDeposits, $tile);

        return $tile;
    }

    /**
     * Generates the add new planet tile.
     *
     *
     * @return string The generate planet tile
     */
    private function newPlanetTile()
    {
        $newTile = $this->newPlanetTileTemplate;

        return $newTile;
    }
}
