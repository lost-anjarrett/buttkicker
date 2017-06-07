<?php
require_once 'ConnexionAbstract.class.php';

class Manager extends ConnexionAbstract
{
  // constructeur
  public function __construct()
  {
    // appel à setDB de l'objet ConnexionAbstract
    $this->setDB();
  }

  // insertion du personnage en BDD
  public function savePerso(Personnage $perso)
  {
    // par défaut l'id est null donc lors de la première sauvegarde on va récupérer l'id
    // généré lors de l'insertion (pour éviter les doublons)
    if (is_null($perso->getId())) {
      $req = $this->request(
          'INSERT INTO personnage (nom, sante, force_perso, xp, niveau, vivant) VALUES (?,?,?,?,?,?)',
          array($perso->getNom(),
                $perso->getSante(),
                $perso->getForce(),
                $perso->getXp(),
                $perso->getNiveau(),
                $perso->getVivant()
          )
        );
      // on récupère l'id avec LAST INSERT ID
      $req = $this->request('SELECT LAST_INSERT_ID() as id FROM personnage');
      $id = $req->fetch();
      // puis on l'assigne au perso
      $perso->setId($id['id']);
    }
    // dans le second cas on UPDATE la BDD en utilisant l'id de l'élément courant
    else {
      $this->request(
            'UPDATE personnage SET nom = ?, sante = ?, force_perso = ?, xp = ?, niveau = ?, vivant = ? WHERE id= ?',
            array(
              $perso->getNom(),
              $perso->getSante(),
              $perso->getForce(),
              $perso->getXp(),
              $perso->getNiveau(),
              $perso->getVivant(),
              $perso->getId()
            )
      );
    }
  }
  // recupération d'un perso sauvegardé grâce à l'ID
  public function recupPerso(int $id)
  {
    $req = $this->request('SELECT * FROM personnage WHERE id = ?', array($id));
    $perso = $req->fetch();
    if (!empty($perso)) {
      return new Personnage(array(
                            'nom' => $perso['nom'],
                            'sante' => $perso['sante'],
                            'force' => $perso['force_perso'],
                            'xp' => $perso['xp'],
                            'niveau' => $perso['niveau'],
                            'id' => $perso['id'],
                            'vivant' => $perso['vivant'])
                          );
    } else {
      echo 'Erreur : cet ID ne correspond à aucun personnage...<br/><br/>';
    }
  }
  public function getAllPerso()
  {
    $req = $this->request('SELECT * FROM personnage WHERE vivant = 1');
    return $req->fetchAll();
  }
  public function getAdversaire(int $id)
  {
    // récupère tous les adversaires potentiels (tous les personnage sauf celui avec ID=$id)
    $req = $this->request('SELECT id FROM personnage WHERE id != ? AND vivant = 1', array($id));
    $adversaires = $req->fetchAll();
    // génère un nbr aléatoire selon la longueur du tableau renvoyé
    $i = rand(0, count($adversaires)-1);
    // renvoie un adversaire grace à ce nombre
    return $this->recupPerso($adversaires[$i]['id']);
  }
}
