<?php

abstract class ConnexionAbstract
{
  const HOST = 'mysql:host=localhost;';
  const DATABASE = 'dbname=buttkicker;charset=utf8';
  const USER = 'buttkicker';
  const PASSWORD = 'ksb';
  private $pdo;

  protected function setDB()
  {
    try {
      $this->pdo = new PDO(
        self::HOST.self::DATABASE,
        self::USER,
        self::PASSWORD,
        [
          // on active le mode d'exception pour afficher plus précisément les erreurs
          // et le mode fetch assoc par défaut pour la récupération des données de la BDD
	    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
      // on force l'affichage en UTF8
      $this->pdo->exec('SET NAMES utf8');
    }
    catch (PDOException $e) {
      die('Erreur : ' .$e->getMessage());
    }
  }

  protected function request($str, array $exec = [])
  {
    if ($exec === []) {
      return $this->pdo->query($str);
    }
    else {
      $req = $this->pdo->prepare($str);
      $req->execute($exec);
      return $req;
    }
  }
}
