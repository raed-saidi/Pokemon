<?php

class AttackPokemon {
    public $attackMinimal;
    public $attackMaximal;
    public $specialAttack;
    public $probabilitySpecialAttack;

    public function __construct($attackMinimal, $attackMaximal, $specialAttack, $probabilitySpecialAttack) {
        $this->attackMinimal = $attackMinimal;
        $this->attackMaximal = $attackMaximal;
        $this->specialAttack = $specialAttack;
        $this->probabilitySpecialAttack = $probabilitySpecialAttack;
    }
}

class Pokemon {
    private $name;
    private $url;
    private $hp;
    public $attackPokemon;

    public function __construct($name, $url, $hp, $attackPokemon) {
        $this->name = $name;
        $this->url = $url;
        $this->hp = $hp;
        $this->attackPokemon = $attackPokemon;
    }

    public function getName() {
        return $this->name;
    }

    public function getHp() {
        return $this->hp;
    }

    public function getUrl() {
        return $this->url;
    }

    public function isDead() {
        return $this->hp <= 0;
    }

    public function attack($target) {
        $damage = rand($this->attackPokemon->attackMinimal, $this->attackPokemon->attackMaximal);
        if (rand(1, 100) <= $this->attackPokemon->probabilitySpecialAttack) {
            $damage *= $this->attackPokemon->specialAttack;
        }
        $newHp = $target->getHp() - $damage;
        $target->setHp(max(0, $newHp));
        return round($damage);
    }

    public function setHp($hp) {
        $this->hp = $hp;
    }

    public function getStatus() {
        return $this->hp;
    }
}
class Fight {
    private $pokemon1;
    private $pokemon2;

    public function __construct($pokemon1, $pokemon2) {
        $this->pokemon1 = $pokemon1;
        $this->pokemon2 = $pokemon2;
    }

    public function startBattle() {
        $log = [];
        $round = 0;

        while (!$this->pokemon1->isDead() && !$this->pokemon2->isDead()) {
            $damage1 = $this->pokemon1->attack($this->pokemon2);
            $damage2 = $this->pokemon2->attack($this->pokemon1);
            $round++;

            $log[] = [[
                "name" => $this->pokemon1->getName(),
                "img" => $this->pokemon1->getUrl(),
                "points" => $this->pokemon1->getStatus(),
                "minAttack" => $this->pokemon1->attackPokemon->attackMinimal,
                "maxAttack" => $this->pokemon1->attackPokemon->attackMaximal,
                "specialAttack" => $this->pokemon1->attackPokemon->specialAttack,
                "probabilitySpecial" => $this->pokemon1->attackPokemon->probabilitySpecialAttack,
                "damage" => $damage1,
                "round" => $round
            ], [
                "name" => $this->pokemon2->getName(),
                "img" => $this->pokemon2->getUrl(),
                "points" => $this->pokemon2->getStatus(),
                "minAttack" => $this->pokemon2->attackPokemon->attackMinimal,
                "maxAttack" => $this->pokemon2->attackPokemon->attackMaximal,
                "specialAttack" => $this->pokemon2->attackPokemon->specialAttack,
                "probabilitySpecial" => $this->pokemon2->attackPokemon->probabilitySpecialAttack,
                "damage" => $damage2,
                "round" => $round
            ]];
        }

        return $log;
    }
}

// Initialiation
$attackPikachu = new AttackPokemon(10, 20, 2, 30);
$attackBulbizarre = new AttackPokemon(8, 15, 1.5, 40);

$pikachu = new Pokemon("Pikachu", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/025.png", 100, $attackPikachu);
$bulbizarre = new Pokemon("Bulbizarre", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/001.png", 100, $attackBulbizarre);

// Simulation
$fight = new Fight($pikachu, $bulbizarre);
$results = $fight->startBattle();

// Output as JSON
header('Content-Type: application/json');
echo json_encode($results);
