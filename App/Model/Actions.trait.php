<?php

trait Actions
{
  // check
  public function checkSante()
  {
    // si la santé est descendue à 0, le perso est mort
    // et on affiche le message en conséquence
    if ($this->getSante() <= 0) {
      $this->setVivant(0);
      echo $this->getNom().' n\'a plus de pts de vie, il/elle est mort(e).<br/><br/>';
      return false;
    } else {
      echo 'Il reste '.$this->getSante().' pts de vie à '.$this->getNom().'...<br/><br/>';
      return true;
    }
  }
  public function checkVivant()
  {
    if ($this->getVivant() == 0) {
      return false;
    } else return true;
  }
  public function checkXp()
  {
    if ($this->xp >= 100) {
      $this->niveau ++;
      $this->xp -= 100;
      echo 'Bravo ! Vous passez au niveau '.$this->niveau.'<br/><br/>';
    }
  }


  // une simple méthode de présentation
  public function presentation()
  {
    echo 'Je m\'appelle '.$this->nom.' j\'ai '.$this->sante.' points de vie,  '.$this->force.' points de force et '.$this->xp.' points d\'XP.<br/><br/>';
  }

  public function soigner()
  {
    $points = rand(1,3)*10 + $this->niveau * 2;
    $this->setSante($this->getSante()+$points);
    $this->setXp(10);
    echo 'Vous vous soignez et regagnez '.$points.' points de vie.<br/>';
    echo '+ 10 XP !<br/><br/>';
    $this->checkXp();
  }

  public function frapper(Personnage $perso)
  {
    // on vérifie que les deux persos sont bien vivants
    if ($this->checkVivant()) {
      if ($perso->checkVivant()) {
        // on calcule les dégats à infliger
        $degats = $this->force + $this->niveau;
        $esquive = rand(0,5);
        if ($esquive == 2) {
          $degats = round($degats/2);
          echo $perso->getNom().' se protège...<br/>';
        }
        elseif ($esquive == 4) {
          $degats = 0;
          echo $perso->getNom().' esquive...<br/>';
        }
        // on redéfinit la nouvelle santé
        $perso->setSante($perso->getSante() - $degats);
        // on ajoute de l'XP
        $this->setXp(10);
        // et enfin on affiche un résumé de l'action
        echo $this->nom.' a infligé '.$degats.' dégats à '.$perso->getNom().'.<br/>';
        echo '+ 10 XP !<br/>';
        // on vérifie l'état de santé
        $perso->checkSante();
        $this->checkXp();
      } else {
        echo '<strong>Attention : </strong>';
        echo 'Vous ne pouvez pas frapper '.$perso->getNom().', il est déjà mort !<br/><br/>';
      }
    } else {
      echo '<strong>Attention : </strong>';
      echo 'Vous ne pouvez plus rien faire, vous êtes mort !<br/><br/>';
    }

  }
}
