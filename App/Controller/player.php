<?php
require_once '../Model/Personnage.class.php';
require_once '../Model/Manager.class.php';


if (isset($_POST['player']) AND $_POST['player'] !== "")
{
  $manager = new Manager();
  if (isset($_POST['advers']) AND $_POST['advers'])
  {
    $player = $manager->getAdversaire($_POST['player']);
  }
  else
  {
    $player = $manager->recupPerso($_POST['player']);
  }
}

?>

<?php if (isset($player)): ?>
  <h4 class="perso-name"><?= $player->getNom() ?></h4>
  <ul>
    <li>Santé : <?= $player->getSante() ?></li>
    <li>Force : <?= $player->getForce() ?></li>
    <li>Niveau : <?= $player->getNiveau() ?></li>
    <li>Expérience : <?= $player->getXp() ?></li>
  </ul>
  <input type="hidden" name="idPlayer" value="<?= $player->getId() ?>">
<?php endif; ?>
