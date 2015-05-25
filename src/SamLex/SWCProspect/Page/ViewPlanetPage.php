<?php

/** Part of SWCProspect, contains ViewPlanetPage class. */
namespace SamLex\SWCProspect\Page;

use SamLex\SWCProspect\Database\DatabaseInteractor;
use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\Deposit;

/**
 * The view planet page.
 *
 * Shows basic information about the planet, a grid showing deposit locations with associated popups
 * and a table showing deposit information with relevant edit and delete buttons. Also includes a
 * planet control (add deposit, edit planet, delete planet) menu.
 */
class ViewPlanetPage extends Page
{
    /** The template for the planet control menu. */
    private $menuPopupTemplate = "
    <div data-role='popup' id='%%PLANET_NAMEID%%MenuPopup'>
        <ul data-role='listview' data-inset='true'>
            <li data-role='list-divider'>Planet Actions</li>
            <li><a href='adddeposit.php?planetid=%%PLANET_ID%%'>Add Deposit</a></li>
            <li><a href='editplanet.php?planetid=%%PLANET_ID%%'>Edit Planet</a></li>
            <li><a onclick='popupChain(\"#%%PLANET_NAMEID%%MenuPopup\", \"#%%PLANET_NAMEID%%DeletePopup\");'>Delete Planet</a></li>
        </ul>
    </div>
    ";

    /** The template for the planet delete popup. */
    private $deletePopupTemplate = "
    <div data-role='popup' id='%%PLANET_NAMEID%%DeletePopup' data-overlay-theme='b'>
        <div data-role='header'>
            <h1>Delete %%PLANET_NAME%%?</h1>
        </div>
        <div data-role='content'>
            <h3 class='ui-title'>Are you sure you want to delete this planet?</h3>
            <p>This will also delete all deposits on this planet and cannot be undone!</p>
            <a data-rel='back' class='ui-btn ui-corner-all ui-btn-inline'>Cancel</a>
            <a href='worker/deleteplanetworker.php?planetid=%%PLANET_ID%%' class='ui-btn ui-corner-all ui-btn-inline' data-ajax='false'>Confirm</a>
        </div>
    </div>
    ";

    /** The template for the deposit delete popup. */
    private $depositDeletePopupTemplate = "
    <div data-role='popup' id='%%DEPOSIT_ID%%DepositDeletePopup' data-overlay-theme='b'>
        <div data-role='header'>
            <h1>Delete this deposit?</h1>
        </div>
        <div data-role='content'>
            <h3 class='ui-title'>Are you sure you want to delete this deposit?</h3>
            <p>This cannot be undone!</p>
            <a data-rel='back' class='ui-btn ui-corner-all ui-btn-inline'>Cancel</a>
            <a href='worker/deletedepositworker.php?depositid=%%DEPOSIT_ID%%' class='ui-btn ui-corner-all ui-btn-inline' data-ajax='false'>Confirm</a>
        </div>
    </div>
    ";

    /** The template for the deposit tooltip popup used in the grid. */
    private $depositTooltipPopupTemplate = "
    <div data-role='popup' id='%%DEPOSIT_ID%%(%%DEPOSIT_COORD%%)TooltipPopup' class='ui-content'>
        <p>%%DEPOSIT_SIZE%% %%DEPOSIT_MAT%%</p>
        <p>(%%DEPOSIT_COORD%%)</p>
    </dv>
    ";

    /** The template for the basic planet info. */
    private $infoGridTemplate = "
    <div class='ui-grid-a ui-responsive'>
        <div class='ui-block-a'>
            <ul data-role='listview' data-inset='true'>
                <li data-role='list-divider' style='text-align:center;'>
                    <h2>Info</h2>
                </li>
                <li>
                    <h2>Name</h2>
                    <p>%%PLANET_NAME%%</p>
                </li>
                <li>
                    <h2>Size</h2>
                    <p>%%PLANET_SIZE%%</p>
                </li>
                <li>
                    <h2>Type</h2>
                    <p>%%PLANET_TYPE%%</p>
                </li>
                <li>
                    <h2>Number of deposits</h2>
                    <p>%%PLANET_NUM_DEPOSITS%%</p>
                </li>
            </ul>
        </div>
        <div class='ui-block-b'>
            <table class='view-planet-page-deposit-grid'>
                <tbody>
                    %%DEPOSIT_BOXES%%
                </tbody>
            </table>
        </div>
    </div>
    ";

    /** The template for a deposit grid cell that has a deposit. */
    private $depositGridFullCellTemplate = "
    <td class='view-planet-page-deposit-grid-cell' style='background-color:%%DEPOSIT_COLOUR%%'>
        <a href='#%%DEPOSIT_ID%%(%%DEPOSIT_COORD%%)TooltipPopup' style='display:block;width:100%;height:100%' title='(%%DEPOSIT_COORD%%)' data-rel='popup' data-transition='pop'>
        </a>
    </td>
    ";

    /** The template for a deposit grid cell that is empty. */
    private $depositGridEmptyCellTemplate = "
    <td class='view-planet-page-deposit-grid-cell'>
    </td>
    ";

    /** The template for the deposit table. */
    private $depositTableTemplate = "
    <table data-role='table' data-mode='reflow' class='ui-body-b ui-shadow ui-responsive table-stripe'>
        <thead>
            <tr class='ui-bar-b'>
                <th>Material</th>
                <th>Location</th>
                <th>Size</th>
                <th style='width:4em;text-align:center;'>Edit?</th>
                <th style='width:6em;text-align:center;'>Delete?</th>
            </tr>
        </thead>
        <tbody>
            %%TABLE_CELLS%%
        </tbody>
    </table>
    ";

    /** The template for a row in the deposit table. */
    private $depositTableCellTemplate = "
    <tr>
        <td style='vertical-align:middle'>%%DEPOSIT_MAT%%</td>
        <td style='vertical-align:middle'>%%DEPOSIT_LOC%%</td>
        <td style='vertical-align:middle'>%%DEPOSIT_SIZE%%</td>
        <td><a href='editdeposit.php?depositid=%%DEPOSIT_ID%%' class='ui-btn ui-mini ui-corner-all'>Edit</a></td>
        <td><a href='#%%DEPOSIT_ID%%DepositDeletePopup' class='ui-btn ui-mini ui-corner-all' data-rel='popup' data-position-to='window'>Delete</a></td>
    </tr>
    ";

    /** The format (sprintf) used to create the menu button. */
    private $menuButtonFormat = "
    <a data-icon='gear' data-iconpos='notext' class='ui-btn-right' href='#%s%dMenuPopup' data-rel='popup'></a>
    ";

    /**
     * The DatabaseInteractor instance to use to get data.
     *
     * @var DatabaseInteractor
     */
    private $db = null;

    /**
     * The planet id of the planet being viewed.
     *
     * @var int
     */
    private $planetID = -1;

    /**
     * Constructs a new MainPage instance.
     *
     * @param DatabaseInteractor $db       The DatabaseInteractor instance to use to get data.
     * @param int                $planetID The planet id of the planet being viewed.
     */
    public function __construct($db, $planetID)
    {
        $this->db = $db;
        $this->planetID = $planetID;
        $this->addToJQHeaderBeforeTitle($this->backButtonTemplate);
    }

    /**
     * Initializes the page.
     *
     * @return bool True if the page initialized successfully.
     */
    public function init()
    {
        $planet = Planet::getPlanet($this->db, $this->planetID);

        if (!is_null($planet)) {
            $deposits = $planet->getDeposits();
            $spacelessName = str_replace(' ', '', $planet->getName());

            $this->setJQPageID(sprintf('swcprospect-view-planet-page-%s%d', $spacelessName, $planet->getDBID()));
            $this->setTitle($planet->getName());
            $this->addToJQHeaderAfterTitle(sprintf($this->menuButtonFormat, $spacelessName, $planet->getDBID()));
            $this->addToJQAfterContent($this->menuPopup($planet, $spacelessName));
            $this->addToJQAfterContent($this->deletePopup($planet, $spacelessName));
            $this->addToJQAfterContent($this->depositDeletePopups($deposits));
            $this->addToJQAfterContent($this->depositTooltipPopups($deposits));
            $this->addToJQContent($this->infoGrid($planet, $deposits));
            $this->addToJQContent($this->depositsTable($deposits));

            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates the planet menu popup.
     *
     * @param Planet $planet        The planet.
     * @param string $spacelessName The name of the planet stripped of spaces.
     *
     * @return string The generated popup.
     */
    private function menuPopup($planet, $spacelessName)
    {
        $popup = $this->menuPopupTemplate;

        $popup = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', $spacelessName, $planet->getDBID()), $popup);
        $popup = str_replace('%%PLANET_ID%%', $planet->getDBID(), $popup);

        return $popup;
    }

    /**
     * Generates the planet delete popup.
     *
     * @param Planet $planet        The planet.
     * @param string $spacelessName The name of the planet stripped of spaces.
     *
     * @return string The generated popup.
     */
    private function deletePopup($planet, $spacelessName)
    {
        $popup = $this->deletePopupTemplate;

        $popup = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', $spacelessName, $planet->getDBID()), $popup);
        $popup = str_replace('%%PLANET_ID%%', $planet->getDBID(), $popup);
        $popup = str_replace('%%PLANET_NAME%%', $planet->getName(), $popup);

        return $popup;
    }

    /**
     * Generates the deposit delete popups.
     *
     * @param Deposit[] $deposits The deposits.
     *
     * @return string The generated popups.
     */
    private function depositDeletePopups($deposits)
    {
        $popups = '';

        foreach ($deposits as $deposit) {
            $popup = $this->depositDeletePopupTemplate;

            $popup = str_replace('%%DEPOSIT_ID%%', $deposit->getDBID(), $popup);

            $popups = $popups.$popup;
        }

        return $popups;
    }

    /**
     * Generates the deposit tooltip popups.
     *
     * @param Deposit[] $deposits The deposits.
     *
     * @return string The generated popups.
     */
    private function depositTooltipPopups($deposits)
    {
        $popups = '';

        foreach ($deposits as $deposit) {
            $popup = $this->depositTooltipPopupTemplate;

            $popup = str_replace('%%DEPOSIT_ID%%', $deposit->getDBID(), $popup);
            $popup = str_replace('%%DEPOSIT_COORD%%', $deposit->getLocationX().','.$deposit->getLocationY(), $popup);
            $popup = str_replace('%%DEPOSIT_SIZE%%', $deposit->getSize(), $popup);
            $popup = str_replace('%%DEPOSIT_MAT%%', $deposit->getType()->getMaterial(), $popup);

            $popups = $popups.$popup;
        }

        return $popups;
    }

    /**
     * Generates the basic info grid (JQ).
     *
     * @param Planet    $planet   The planet.
     * @param Deposit[] $deposits The deposits.
     *
     * @return string The generated JQ grid.
     */
    private function infoGrid($planet, $deposits)
    {
        $info = $this->infoGridTemplate;

        $info = str_replace('%%PLANET_NAME%%', $planet->getName(), $info);
        $info = str_replace('%%PLANET_SIZE%%', sprintf('%1$dx%1$d', $planet->getSize()), $info);
        $info = str_replace('%%PLANET_TYPE%%', $planet->getType()->getDescription(), $info);
        $info = str_replace('%%PLANET_NUM_DEPOSITS%%', count($deposits), $info);

        $info = str_replace('%%DEPOSIT_BOXES%%', $this->depositsGrid($deposits, $planet->getSize()), $info);

        return $info;
    }

    /**
     * Generates the deposit grid.
     *
     * @param Deposit[] $deposits   The deposits.
     * @param int       $planetSize The size of the planet.
     *
     * @return string The generated grid.
     */
    private function depositsGrid($deposits, $planetSize)
    {
        $depositCoordArray = array();

        foreach ($deposits as $deposit) {
            $depositCoordArray[$deposit->getLocationX().','.$deposit->getLocationY()] = $deposit;
        }

        $boxes = '';

        for ($row = 0;$row < $planetSize;$row++) {
            $boxes = $boxes.'<tr>';

            for ($col = 0;$col < $planetSize;$col++) {
                $coord = $row.','.$col;

                if (array_key_exists($coord, $depositCoordArray)) {
                    $cell = $this->depositGridFullCellTemplate;
                    $deposit = $depositCoordArray[$coord];

                    $cell = str_replace('%%DEPOSIT_COLOUR%%', $deposit->getType()->getHTMLColour(), $cell);
                    $cell = str_replace('%%DEPOSIT_COORD%%', $coord, $cell);
                    $cell = str_replace('%%DEPOSIT_ID%%', $deposit->getDBID(), $cell);
                } else {
                    $cell = $this->depositGridEmptyCellTemplate;
                }

                $boxes = $boxes.$cell;
            }

            $boxes = $boxes.'</tr>';
        }

        return $boxes;
    }

    /**
     * Generates the deposit table.
     *
     * @param Deposit[] $deposits The deposits.
     *
     * @return string The generated table.
     */
    private function depositsTable($deposits)
    {
        $table = $this->depositTableTemplate;

        $cells = '';

        foreach ($deposits as $deposit) {
            $cell = $this->depositTableCellTemplate;

            $cell = str_replace('%%DEPOSIT_MAT%%', $deposit->getType()->getMaterial(), $cell);
            $cell = str_replace('%%DEPOSIT_LOC%%', sprintf('(%d, %d)', $deposit->getLocationX(), $deposit->getLocationY()), $cell);
            $cell = str_replace('%%DEPOSIT_SIZE%%', $deposit->getSize(), $cell);
            $cell = str_replace('%%DEPOSIT_ID%%', $deposit->getDBID(), $cell);

            $cells = $cells.$cell;
        }

        $table = str_replace('%%TABLE_CELLS%%', $cells, $table);

        return $table;
    }
}
