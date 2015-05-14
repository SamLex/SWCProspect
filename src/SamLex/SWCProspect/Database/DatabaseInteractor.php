<?php

namespace SamLex\SWCProspect\Database;

interface DatabaseInteractor
{
    public function isAvailable();

    public function getDepositType($id);
    public function getPlanetType($id);
    public function getDeposit($id);
    public function getPlanet($id);
    public function getPlanets();
    public function getDeposits($planetID);
    public function getNumDeposits($planetID);

    public function savePlanet($planet);
    public function saveDeposit($deposit);

    public function deletePlanet($planet);
    public function deleteDeposit($deposit);
}
