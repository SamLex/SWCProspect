<?php

namespace SamLex\SWCProspect\Database;

interface DatabaseInteractor
{
    public function getPlanets();
    public function getResults($planet);
    public function savePlanet($planet);
    public function saveResult($result);
}
