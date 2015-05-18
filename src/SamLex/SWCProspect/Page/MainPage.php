<?php

namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Planet;

/*
    The main landing page
*/
class MainPage extends Page
{
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
    
    public function __construct($db)
    {
        $dbError = !$db->isAvailable();
        
        if($dbError === false)
        {
            $planets = $db->getPlanets();
            
            if($planets === false)
            {
                $dbError = true;
            }
            else
            {
                $depositNums = array();
                
                foreach($planets as $planet)
                {
                    $numDeposits = $db->getNumDeposits($planet->getDBID());
                    
                    if($numDeposits === false)
                    {
                        $dbError = true;
                        break;
                    }
                    else
                    {
                        $depositNums[$planet->getName()] = $numDeposits;
                    }
                }    
            }
        }
        
        $this->setJQPageID('swcprospect-main-page');
        $this->setTitle('Welcome to SWCProspect');
        
        if($dbError === true)
        {
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        }
        else
        {
            foreach($planets as $planet)
            {
                $this->addToJQContent($this->planetTile($planet, $depositNums[$planet->getName()]));
            }
            
            $this->addToJQContent($this->newPlanetTile());
        }
    }

    /*
        Replaces to placeholders in planet tile template and returns the resulting string
    */
    private function planetTile($planet, $numDeposits)
    {
        $showSize = 5 + (($planet->getSize() - 1) * (40 / 19));

        $tile = $this->planetTileTemplate;
        
        $tile = str_replace('%%PLANET_ID%%', $planet->getDBID(), $tile);
        $tile = str_replace('%%PLANET_NAME%%', $planet->getName(), $tile);
        $tile = str_replace('%%PLANET_SHOW_SIZE%%', $showSize, $tile);
        $tile = str_replace('%%PLANET_COLOUR%%', $planet->getType()->getHTMLColour(), $tile);
        $tile = str_replace('%%PLANET_NUM_DEPOSITS%%', $numDeposits, $tile);
        
        return $tile;
    }

     /*
        Replaces to placeholders in new planet tile template and returns the resulting string
    */
    private function newPlanetTile()
    {
        $newTile = $this->newPlanetTileTemplate;
        
        return $newTile;
    }
}
