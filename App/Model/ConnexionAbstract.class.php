<?php

abstract class ConnexionAbstract
{
  const HOST = 'mysql:host=localhost;dbname=buttkicker;charset=utf8';
  const USER = 'buttkicker';
  const PASSWORD = 'ksb';
  private $pdo;

  // une méthode pour initaliser la BD
  protected function setDB()
  {
    try {
      //  on se connecte à la BDD créée via PHPMyAdmin
      $this->pdo = new PDO(
        self::HOST,
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
      // en cas d'erreur on affiche un message d'erreur personnalisé avec l'erreur correspondante
      die('Erreur : ' .$e->getMessage());
    }
  }

  // une méthode pour faire les requêtes
  protected function request($str, array $exec = null)
  {
    // s'il n'y a pas de données à injecter dans la requête, on fait un query
    if ($exec === null) {
      return $this->pdo->query($str);
    }
    // sinon on fait une requête préparée
    else {
      $req = $this->pdo->prepare($str);
      $req->execute($exec);
      return $req;
    }
  }
}
