<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';


if (isset($_POST["name"]) AND !empty($_POST["name"])) {
  // création du nouveau perso
  if ($_POST["genre"] == 'tordu') {
    $perso = new Personnage(array(
      'nom'=>$_POST["name"],
      'force'=>15,
      'sante'=>150
    ));
  } elseif ($_POST["genre"] == 'bourrin') {
    $perso = new Personnage(array(
      'nom'=>$_POST["name"],
      'force'=>25,
      'sante'=>100
    ));
  } else {
    $error = 'unknown';
  }
  // sauvegarde du nouveau perso dans le base de donnée
  if ($perso) {
    $manager = new Manager;
    $manager->savePerso($perso);
  }
}
// affichage pour le retour :
if ($perso): ?>
  <option value="<?= $perso->getId() ?>"><?= $perso->getNom() ?></option>
<?php endif; ?>
