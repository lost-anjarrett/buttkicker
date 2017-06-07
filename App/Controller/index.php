<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

$manager = new Manager();
//
// $trofor = $manager->recupPerso(15);
// $bigboul = $manager->recupPerso(16);
// $frieda = $manager->recupPerso(18);
// echo '<pre>';
// var_dump($frieda);
// echo '</pre>';

// on récupère tous les persos dispos en base de donnée
$all_perso = $manager->getAllPerso();



include '../../Public/View/index.phtml';
