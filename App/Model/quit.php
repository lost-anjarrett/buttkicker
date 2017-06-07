<?php
require_once 'Personnage.class.php';
require_once 'Manager.class.php';

extract($_GET);

$manager = new Manager;

$player = $manager->recupPerso($idPlayer);
$adversaire = $manager->recupPerso($idAdvers);

$manager->savePerso($player);
$manager->savePerso($adversaire);

header('Location:../Controller/index.php');
