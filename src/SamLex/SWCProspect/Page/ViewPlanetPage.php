<?php

namespace SamLex\SWCProspect\Page;

/*
    The view planet page
*/
class ViewPlanetPage extends Page
{
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

    private $depositTooltipPopupTemplate = "
    <div data-role='popup' id='%%DEPOSIT_ID%%(%%DEPOSIT_COORD%%)TooltipPopup' class='ui-content'>
        <p>%%DEPOSIT_SIZE%% %%DEPOSIT_MAT%%</p>
        <p>(%%DEPOSIT_COORD%%)</p>
    </dv>
    ";

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

    private $depositGridFullCellTemplate = "
    <td class='view-planet-page-deposit-grid-cell' style='background-color:%%DEPOSIT_COLOUR%%'>
        <a href='#%%DEPOSIT_ID%%(%%DEPOSIT_COORD%%)TooltipPopup' style='display:block;width:100%;height:100%' title='(%%DEPOSIT_COORD%%)' data-rel='popup' data-transition='pop'>
        </a>
    </td>
    ";

    private $depositGridEmptyCellTemplate = "
    <td class='view-planet-page-deposit-grid-cell'>
    </td>
    ";

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

    private $depositTableCellTemplate = "
    <tr>
        <td style='vertical-align:middle'>%%DEPOSIT_MAT%%</td>
        <td style='vertical-align:middle'>%%DEPOSIT_LOC%%</td>
        <td style='vertical-align:middle'>%%DEPOSIT_SIZE%%</td>
        <td><a href='editdeposit.php?depositid=%%DEPOSIT_ID%%' class='ui-btn ui-mini ui-corner-all'>Edit</a></td>
        <td><a href='#%%DEPOSIT_ID%%DepositDeletePopup' class='ui-btn ui-mini ui-corner-all' data-rel='popup' data-position-to='window'>Delete</a></td>
    </tr>
    ";

    public function __construct($db, $planetID)
    {
        $dbError = !$db->isAvailable();

        if ($dbError === false) {
            $planet = $db->getPlanet($planetID);

            if ($planet === false) {
                $dbError = true;
            } else {
                $deposits = $db->getDeposits($planet->getDBID());

                if ($deposits === false) {
                    $dbError = true;
                }
            }
        }

        $this->addToJQHeaderBeforeTitle("<a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>");

        if ($dbError === true) {
            $this->setJQPageID('swcprospect-view-planet-page-error');
            $this->setTitle('Error');
            $this->addToJQContent('<p><b>Database error. Unable to continue.</b></p>');
        } else {
            $this->setJQPageID(sprintf('swcprospect-view-planet-page-%s%d', str_replace(' ', '', $planet->getName()), $planet->getDBID()));
            $this->setTitle($planet->getName());
            $this->addToJQHeaderAfterTitle(sprintf("<a data-icon='gear' data-iconpos='notext' class='ui-btn-right' href='#%s%dMenuPopup' data-rel='popup'></a>", str_replace(' ', '', $planet->getName()), $planet->getDBID()));
            $this->addToJQAfterContent($this->menuPopup($planet));
            $this->addToJQAfterContent($this->deletePopup($planet));
            $this->addToJQAfterContent($this->depositDeletePopups($deposits));
            $this->addToJQAfterContent($this->depositTooltipPopups($deposits));
            $this->addToJQContent($this->infoGrid($planet, $deposits));
            $this->addToJQContent($this->depositsTable($deposits));
        }
    }

    private function menuPopup($planet)
    {
        $popup = $this->menuPopupTemplate;

        $popup = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', str_replace(' ', '', $planet->getName()), $planet->getDBID()), $popup);
        $popup = str_replace('%%PLANET_ID%%', $planet->getDBID(), $popup);

        return $popup;
    }

    private function deletePopup($planet)
    {
        $popup = $this->deletePopupTemplate;

        $popup = str_replace('%%PLANET_NAMEID%%', sprintf('%s%d', str_replace(' ', '', $planet->getName()), $planet->getDBID()), $popup);
        $popup = str_replace('%%PLANET_ID%%', $planet->getDBID(), $popup);
        $popup = str_replace('%%PLANET_NAME%%', $planet->getName(), $popup);

        return $popup;
    }

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
