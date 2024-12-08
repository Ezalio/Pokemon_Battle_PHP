<?php

require_once 'Pokemon.php';

class PokemonFeu extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Feu', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Plante') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Lance-Flammes sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonEau extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Eau', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Feu') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Hydrocanon sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonPlante extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Plante', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Eau') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Fouet-Lianes sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonElectrik extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Electrik', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Eau') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Éclair sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonNormal extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Normal', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $degats = max(0, $this->puissanceAttaque - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Charge sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonCombat extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Combat', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Normal') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Poing-Karaté sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}

class PokemonPsy extends Pokemon {
    public function __construct($nom, $pointsDeVie, $puissanceAttaque, $defense) {
        parent::__construct($nom, 'Psy', $pointsDeVie, $puissanceAttaque, $defense);
    }

    public function capaciteSpeciale(Pokemon $adversaire) {
        $bonus = ($adversaire->type === 'Combat') ? 20 : 0;
        $degats = max(0, $this->puissanceAttaque + $bonus - $adversaire->defense);
        $adversaire->recevoirDegats($degats);
        return "{$this->nom} utilise Choc Mental sur {$adversaire->nom}, infligeant {$degats} dégâts!";
    }
}
