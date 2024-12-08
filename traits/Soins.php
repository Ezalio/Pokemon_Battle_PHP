<?php

trait Soins {
    public function soigner() {
        $this->pointsDeVie = $this->maxPointsDeVie;
        return "{$this->nom} se soigne et retrouve tous ses points de vie!";
    }
}

