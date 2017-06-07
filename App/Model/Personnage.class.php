<?php
require_once 'Actions.trait.php';

class Personnage
{
  use Actions;

  private $nom;
  private $sante = 100;
  private $force = 15;
  private $xp = 0;
  private $niveau =0;
  private $id = null;
  private $vivant = 1;

  // constructeur
  public function __construct(array $data)
  {
    // la bonne pratique veut que l'on fasse appel aux setters plutôt qu'insérer directement
    //  (les setters peuvent avoir des structures de contrôle : is_int, preg_match, etc)
    $this->hydrate($data);
  }
  // la fonction d'hydratation sert au constructeur
  // de plus ça nous permet de rendre certains paramètres optionnels
  // dans ce cas tous les paramètres sauf le nom sont en option
  private function hydrate(array $data)
  {
    // on vérifie qu'on a au moins rentré un nom pour le personnage
    if (array_key_exists('nom', $data)) {
      // dans ce cas pour parcourt le tableau associatif
      foreach ($data as $key => $value)
      {
        // on construit une chaîne de caractère qui va nous permettre d'appeler les setters
        // en fonction des clés du tableau entré
        // exemple : array('nom'=>'Toto') on récupère 'nom' puis $method = setNom
        $method = 'set'.ucfirst($key);
        // si la méthode existe pour la classe courante on peut y faire appel
        if (method_exists($this, $method)) {
          // dans notre exemple setNom est bien une méthode de la classe Personnage
          // donc on peut l'utiliser en récupérent $value qui ici serait 'Toto'
          $this->$method($value);
        }
      }
    } else {
      trigger_error('Vous devez rentrer au moins un nom');
    }

  }

  // getters
  public function getNom()
  {
    return $this->nom;
  }
  public function getSante()
  {
    return $this->sante;
  }
  public function getForce()
  {
    return $this->force;
  }
  public function getXp()
  {
    return $this->xp;
  }
  public function getNiveau()
  {
    return $this->niveau;
  }
  public function getId()
  {
    return $this->id;
  }
  public function getVivant()
  {
    return $this->vivant;
  }

  // setters
  public function setNom($nom)
  {
    $this->nom = $nom;
  }
  public function setSante($pts)
  {
    $this->sante = $pts;
  }
  public function setForce($force)
  {
    $this->force = $force;
  }
  public function setXp($xp)
  {
    $this->xp += $xp;
  }
  public function setNiveau($niveau)
  {
    $this->niveau += $niveau;
  }
  public function setId($id)
  {
    // on ajoute une vérification pour éviter de réécrire un ID déjà existant
    // (pour éviter des erreurs ensuite avec la BDD)
    // donc dans le cas d'une initialisation (= quand id est null) on affecte la valeur
    if ($this->id === null) {
      $this->id = $id;
    } else {
      // et sinon on renvoie un message d'erreur avec trigger_error
      // (pas de 2eme argument, par défaut le type d'erreur est NOTICE)
      trigger_error('Erreur : ce personnage est déjà enregistré !');
      // ceci nous sert car on ne peut pas passer la fonction setId en private
      // car la classe Manager l'utilise au moment de la 1ère inscription en BDD
    }
  }
  public function setVivant($life)
  {
    $this->vivant = $life;
  }

  

}
