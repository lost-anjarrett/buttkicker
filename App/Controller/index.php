<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';

$manager = new Manager();

$all_perso = $manager->getAllPerso();



include '../../Public/View/index.phtml';
