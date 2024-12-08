<?php

require_once './interfaces/Combattant.php';
require_once './traits/Soins.php';

abstract class Pokemon implements Combattant {
    use Soins;

    protected $nom;
    protected $type;
    protected $pointsDeVie;
    protected $maxPointsDeVie;
    protected $puissanceAttaque;
    protected $defense;

    public function __construct($nom, $type, $pointsDeVie, $puissanceAttaque, $defense) {
        $this->nom = $nom;
        $this->type = $type;
        $this->pointsDeVie = $pointsDeVie;
        $this->maxPointsDeVie = $pointsDeVie;
        $this->puissanceAttaque = $puissanceAttaque;
        $this->defense = $defense;
    }

    public function attaquer(Pokemon $adversaire) {
        $degats = max(0, $this->puissanceAttaque - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return $degats;
    }

    public function recevoirDegats($degats) {
        $this->pointsDeVie = max(0, $this->pointsDeVie - $degats);
    }

    public function estKO() {
        return $this->pointsDeVie <= 0;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPointsDeVie() {
        return $this->pointsDeVie;
    }

    public function getMaxPointsDeVie() {
        return $this->maxPointsDeVie;
    }

    public function getPuissanceAttaque() {
        return $this->puissanceAttaque;
    }

    public function getDefense() {
        return $this->defense;
    }

    public function seBattre(Pokemon $adversaire) {
        $degats = $this->attaquer($adversaire);
        return "{$this->nom} attaque {$adversaire->getNom()} et inflige {$degats} dégâts!";
    }

    public function utiliserAttaqueSpeciale(Pokemon $adversaire) {
        return $this->capaciteSpeciale($adversaire);
    }

    abstract public function capaciteSpeciale(Pokemon $adversaire);
    
}
