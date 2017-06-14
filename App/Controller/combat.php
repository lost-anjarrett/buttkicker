<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

extract($_POST);
var_dump($_POST);
$manager = new Manager;
$player = $manager->recupPerso($idPlayer);
$adversaire = $manager->recupPerso($idAdvers);

include '../../Public/View/combat.phtml';
