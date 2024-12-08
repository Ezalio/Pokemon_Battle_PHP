<?php

class Combat
{
    private $pokemon1; // Pokémon du joueur
    private $pokemon2; // Pokémon de l'adversaire

    public function __construct(Pokemon $pokemon1, Pokemon $pokemon2)
    {
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }

    public function getPokemon1()
    {
        return $this->pokemon1;
    }

    public function getPokemon2()
    {
        return $this->pokemon2;
    }

    public function processPlayerAction($action, Pokemon $playerPokemon, Pokemon $opponentPokemon)
    {
        switch ($action) {
            case 1: // Attaque normale
                return $playerPokemon->seBattre($opponentPokemon);
            case 2: // Attaque spéciale
                return $playerPokemon->utiliserAttaqueSpeciale($opponentPokemon);
            case 3: // Soin
                $playerPokemon->soigner();
                return "{$playerPokemon->getNom()} se soigne et retrouve toute sa vie!";
            default:
                return "Action invalide!";
        }
    }

    public function afficherBarreVie(Pokemon $pokemon)
    {
        $hpPercentage = ($pokemon->getPointsDeVie() / 100) * 100; 
        return "<div style='width: 200px; background: #ddd; border: 1px solid #ccc;'>
                    <div style='width: {$hpPercentage}%; background: green; height: 20px;'></div>
                </div>";
    }

    public function processOpponentAction(Pokemon $pokemon2, Pokemon $pokemon1)
    {
        // Actions possibles pour l'adversaire
        $actions = ['attack', 'attack', 'special']; 

        // Choisir une action au hasard
        $randomAction = $actions[array_rand($actions)];

        switch ($randomAction) {
            case 'attack':
                $degats = $pokemon2->attaquer($pokemon1);
                return "{$pokemon2->getNom()} attaque {$pokemon1->getNom()} et inflige {$degats} dégâts!";
            
            case 'special':
                $message = $pokemon2->capaciteSpeciale($pokemon1);
                return $message;

            case 'heal':
                $message = $pokemon2->soigner();
                return "{$pokemon2->getNom()} utilise une potion et restaure ses PV!";
            
            default:
                return "{$pokemon2->getNom()} semble hésiter...";
        }
    }

    public function determinerVainqueur()
    {
        if ($this->pokemon1->estKO()) {
            return "{$this->pokemon2->getNom()} a gagné!";
        } elseif ($this->pokemon2->estKO()) {
            return "{$this->pokemon1->getNom()} a gagné!";
        }
        return "Match nul!";
    }
}
