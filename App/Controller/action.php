<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

if (isset($_POST) && !empty($_POST)) {
    extract($_POST);

    if (is_numeric($idPlayer) && is_numeric($idAdvers)) {
        $manager = new Manager;
        $player = $manager->recupPerso($idPlayer);
        $advers = $manager->recupPerso($idAdvers);

        switch ($action) {
            case 'hit' :
                $player->frapper($advers);
                $manager->savePerso($player);
                $manager->savePerso($advers);
                break;
            case 'heal' :
                $player->soigner();
                $manager->savePerso($player);
                $manager->savePerso($advers);
                break;
        }

    }

}
