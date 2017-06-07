<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

extract($_POST);
$manager = new Manager;
$player = $manager->recupPerso($idPlayer);
$adversaire = $manager->recupPerso($idAdversaire);

include '../../Public/View/combat.phtml';
