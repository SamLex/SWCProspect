<?php

namespace SamLex\SWCProspect\Page;

/*
    The view planet page
*/
class ViewPlanetPage extends Page
{
    private $dbInteractor;
    private $planet;
    private $deposits;

    public function __construct($dbInteractor, $planetID)
    {
        $this->dbInteractor = $dbInteractor;
        $this->planet = $this->dbInteractor->getPlanet($planetID);
        $this->deposits = $this->dbInteractor->getDeposits($this->planet->getDBID());
    }

    public function startBody()
    {
        parent::startBody();

        printf("
        <div data-role='page' data-theme='b' id='view-planet-%1\$s'>
            <div data-role='header' data-position='inline'>
                <a data-icon='back' data-iconpos='notext' class='ui-btn-left' data-rel='back'></a>
                <h1>%1\$s</h1>
                <a data-icon='gear' data-iconpos='notext' class='ui-btn-right' href='#%1\$sMenuPopup' data-rel='popup'></a>
            </div>
            <div data-role='popup' id='%1\$sMenuPopup'>
                <ul data-role='listview' data-inset='true'>
                    <li data-role='list-divider'>Planet Actions</li>
                    <li><a href='adddeposit.php?planetid=%5\$d'>Add Deposit</a></li>
                    <li><a href='editplanet.php?planetid=%5\$d'>Edit Planet</a></li>
                    <li><a onclick='popupChain(\"#%1\$sMenuPopup\", \"#delete%1\$sPopup\");'>Delete Planet</a></li>
                </ul>
            </div>
            <div data-role='popup' id='delete%1\$sPopup' data-overlay-theme='b'>
                <div data-role='header'>
                    <h1>Delete %1\$s?</h1>
                </div>
                <div data-role='content'>
                    <h3 class='ui-title'>Are you sure you want to delete this planet?</h3>
                    <p>This will also delete all deposits on this planet and cannot be undone!</p>
                    <a data-rel='back' class='ui-btn ui-corner-all ui-btn-inline'>Cancel</a>
                    <a href='deleteplanetworker.php?planetid=%5\$d' class='ui-btn ui-corner-all ui-btn-inline' data-ajax='false'>Confirm</a>
                </div>
            </div>
            <div data-role='content'>
                <div class='ui-grid-a ui-responsive'>
                    <div class='ui-block-a'>
                        <ul data-role='listview' data-inset='true'>
                            <li data-role='list-divider' style='text-align:center;'>
                                <h2>Info</h2>
                            </li>
                            <li>
                                <h2>Name</h2>
                                <p>%1\$s</p>
                            </li>
                            <li>
                                <h2>Size</h2>
                                <p>%2\$d</p>
                            </li>
                            <li>
                                <h2>Type</h2>
                                <p>%3\$s</p>
                            </li>
                            <li>
                                <h2>Number of deposits</h2>
                                <p>%4\$d</p>
                            </li>
                        </ul>
                    </div>
                    <div class='ui-block-b'>
        ",
        $this->planet->getName(),
        $this->planet->getSize(),
        $this->planet->getType()->getDescription(),
        $this->dbInteractor->getNumDeposits($this->planet->getDBID()),
        $this->planet->getDBID());

        $this->depositGrid();

        printf('
                    </div>
        ');

        $this->depositList();

        printf('
                </div>
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

    private function depositGrid()
    {
        $slotSizePercent = 100 / $this->planet->getSize();

        printf("
                        <div class='view-planet-page-deposit-grid-container'>
                            <svg class='view-planet-page-deposit-grid'>
        ");

        foreach ($this->deposits as $deposit) {
            printf("
                                <rect x='%1\$f%%' y='%2\$f%%' width='%3\$f%%' height='%3\$f%%' fill='%4\$s' />
            ",
            $deposit->getLocationX() * $slotSizePercent,
            $deposit->getLocationY() * $slotSizePercent,
            $slotSizePercent,
            $deposit->getType()->getHTMLColour(),
            $this->planet->getName(),
            $deposit->getLocationX(),
            $deposit->getLocationY());
        }

        for ($x = 0;$x <= $this->planet->getSize();$x++) {
            printf("
                                <line x1='%1\$f%%' y1='0%%' x2='%1\$f%%' y2='100%%' style='stroke:#000000;stroke-width:1' />
            ", $x * $slotSizePercent);
        }

        for ($y = 0;$y <= $this->planet->getSize();$y++) {
            printf("
                                <line x1='0%%' y1='%1\$f%%' x2='100%%' y2='%1\$f%%' style='stroke:#000000;stroke-width:1' />
            ", $y * $slotSizePercent);
        }

        printf('
                            </svg>
                        </div>
        ');
    }

    private function depositList()
    {
        printf("
                    <div>
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
        ");

        foreach ($this->deposits as $deposit) {
            printf("
                                <tr>
                                    <td style='vertical-align:middle'>%1\$s</td>
                                    <td style='vertical-align:middle'>%2\$dx%3\$d</td>
                                    <td style='vertical-align:middle'>%4\$d</td>
                                    <td><a href='editdeposit.php?depositid=%5\$d' class='ui-btn ui-mini ui-corner-all'>Edit</a></td>
                                    <td><a href='#deleteDeposit%5\$dPopup' class='ui-btn ui-mini ui-corner-all' data-rel='popup'>Delete</a></td>
                                </tr>
            ",
            $deposit->getType()->getMaterial(),
            $deposit->getLocationX(),
            $deposit->getLocationY(),
            $deposit->getSize(),
            $deposit->getDBID());
        }

        printf('
                            </tbody>
                        </table>
                    </div>
        ');
    }
}
