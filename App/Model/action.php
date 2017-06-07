<?php
require_once 'Personnage.class.php';
require_once 'Manager.class.php';

extract($_POST);
$manager = new Manager;
$player = $manager->recupPerso($idPlayer);
$adversaire = $manager->recupPerso($idAdversaire);

if ($action == 'hit') {
  $player->frapper($adversaire);
  $manager->savePerso($player);
  $manager->savePerso($adversaire);
}
elseif ($action == 'heal') {
  $player->soigner();
  $manager->savePerso($player);
  $manager->savePerso($adversaire);
}
