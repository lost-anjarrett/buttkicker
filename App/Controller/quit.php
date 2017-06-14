<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

extract($_GET);

$manager = new Manager;

$player = $manager->recupPerso($idPlayer);
$adversaire = $manager->recupPerso($idAdvers);

$manager->savePerso($player);
$manager->savePerso($adversaire);

header('Location: index.php');
