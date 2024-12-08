<?php
require_once 'classes/Pokemon.php';
require_once 'classes/PokemonTypes.php';
require_once 'classes/Combat.php';

session_start();

// Réinitialiser le jeu si demandé
if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    session_destroy();
    header("Location: form.php");
    exit();
}

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pokemon1']) && isset($_POST['pokemon2'])) {
    $pokemon1Name = $_POST['pokemon1'];
    $pokemon2Name = $_POST['pokemon2'];

    // Instancier les Pokémon
    try {
        switch ($pokemon1Name) {
            case 'Salamèche':
                $pokemon1 = new PokemonFeu("Salamèche", 100, 20, 10);
                break;
            case 'Carapuce':
                $pokemon1 = new PokemonEau("Carapuce", 100, 15, 12);
                break;
            case 'Bulbizarre':
                $pokemon1 = new PokemonPlante("Bulbizarre", 100, 18, 8);
                break;
            case 'Pikachu':
                $pokemon1 = new PokemonElectrik("Pikachu", 100, 18, 10);
                break;
            case 'Rattata':
                $pokemon1 = new PokemonNormal("Rattata", 50, 10, 5);
                break;
            case 'Évoli':
                $pokemon1 = new PokemonNormal("Évoli", 90, 15, 8);
                break;
            case 'Machoc':
                $pokemon1 = new PokemonCombat("Machoc", 120, 20, 15);
                break;
            case 'Mew':
                $pokemon1 = new PokemonPsy("Mew", 150, 25, 20);
                break;
            case 'Goupix':
                $pokemon2 = new PokemonFeu("Goupix", 90, 15, 8);
                break;
            default:
                throw new Exception("Erreur : Pokémon sélectionné pour le Joueur 1 invalide. Reçu : $pokemon1Name");
        }

        switch ($pokemon2Name) {
            case 'Salamèche':
                $pokemon2 = new PokemonFeu("Salamèche", 100, 20, 10);
                break;
            case 'Carapuce':
                $pokemon2 = new PokemonEau("Carapuce", 100, 15, 12);
                break;
            case 'Bulbizarre':
                $pokemon2 = new PokemonPlante("Bulbizarre", 100, 18, 8);
                break;
            case 'Pikachu':
                $pokemon2 = new PokemonElectrik("Pikachu", 100, 18, 10);
                break;
            case 'Rattata':
                $pokemon2 = new PokemonNormal("Rattata", 50, 10, 5);
                break;
            case 'Évoli':
                $pokemon2 = new PokemonNormal("Évoli", 90, 15, 8);
                break;
            case 'Machoc':
                $pokemon2 = new PokemonCombat("Machoc", 120, 20, 15);
                break;
            case 'Mew':
                $pokemon2 = new PokemonPsy("Mew", 150, 25, 20);
                break;
            case 'Goupix':
                $pokemon2 = new PokemonFeu("Goupix", 90, 15, 8);
                break;
            default:
                throw new Exception("Erreur : Pokémon sélectionné pour le Joueur 1 invalide. Reçu : $pokemon1Name");
        }

        // Initialiser la session de combat
        $_SESSION['combat'] = new Combat($pokemon1, $pokemon2);
        $_SESSION['playerTurn'] = true;
        $_SESSION['combatLog'] = [];

        // Rediriger vers la page de combat
        header("Location: battle.php");
        exit();
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}

// Liste des Pokémon à afficher
$pokemonList = [
    ['name' => 'Salamèche', 'type' => 'Feu', 'image' => 'images/salamèche.png', 'hp' => 100, 'attack' => 20, 'defense' => 10],
    ['name' => 'Carapuce', 'type' => 'Eau', 'image' => 'images/carapuce.png', 'hp' => 100, 'attack' => 15, 'defense' => 12],
    ['name' => 'Bulbizarre', 'type' => 'Plante', 'image' => 'images/bulbizarre.png', 'hp' => 100, 'attack' => 18, 'defense' => 8],
    ['name' => 'Pikachu', 'type' => 'Electrik', 'image' => 'images/pikachu.png', 'hp' => 100, 'attack' => 18, 'defense' => 10],
    ['name' => 'Rattata', 'type' => 'Normal', 'image' => 'images/rattata.png', 'hp' => 50, 'attack' => 10, 'defense' => 5],
    ['name' => 'Évoli', 'type' => 'Normal', 'image' => 'images/évoli.png', 'hp' => 90, 'attack' => 15, 'defense' => 8],
    ['name' => 'Machoc', 'type' => 'Combat', 'image' => 'images/machoc.png', 'hp' => 120, 'attack' => 20, 'defense' => 15],
    ['name' => 'Mew', 'type' => 'Psy', 'image' => 'images/mew.png', 'hp' => 150, 'attack' => 25, 'defense' => 20],
    ['name' => 'Goupix', 'type' => 'Feu', 'image' => 'images/goupix.png', 'hp' => 90, 'attack' => 15, 'defense' => 8]
];
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un Pokémon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="animations.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Choisissez vos Pokémon</h1>
        <form action="form.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <!-- Joueur Pokémon -->
            <h2 class="text-xl font-bold mb-4">Sélectionnez Pokémon 1 (Joueur) :</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <?php foreach ($pokemonList as $key => $pokemon): ?>
                    <label class="pokemon-card block border border-gray-300 rounded-md p-4 cursor-pointer">
                        <input type="radio" name="pokemon1" value="<?= $pokemon['name'] ?>" class="hidden" required>
                        <img src="<?= $pokemon['image'] ?>" alt="<?= $pokemon['name'] ?>" class="w-16 h-16 mx-auto">
                        <h3 class="text-center font-bold"><?= $pokemon['name'] ?></h3>
                        <p class="text-center text-sm text-gray-600">Type : <?= $pokemon['type'] ?></p>
                        <p class="text-center text-sm text-gray-600">PV : <?= $pokemon['hp'] ?></p>
                        <p class="text-center text-sm text-gray-600">Attaque : <?= $pokemon['attack'] ?></p>
                        <p class="text-center text-sm text-gray-600">Défense : <?= $pokemon['defense'] ?></p>
                    </label>
                <?php endforeach; ?>
            </div>

            <!-- Adversaire Pokémon -->
            <h2 class="text-xl font-bold mb-4">Sélectionnez Pokémon 2 (Adversaire) :</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <?php foreach ($pokemonList as $key => $pokemon): ?>
                    <label class="pokemon-card block border border-gray-300 rounded-md p-4 cursor-pointer">
                        <input type="radio" name="pokemon2" value="<?= $pokemon['name'] ?>" class="hidden" required>
                        <img src="<?= $pokemon['image'] ?>" alt="<?= $pokemon['name'] ?>" class="w-16 h-16 mx-auto">
                        <h3 class="text-center font-bold"><?= $pokemon['name'] ?></h3>
                        <p class="text-center text-sm text-gray-600">Type : <?= $pokemon['type'] ?></p>
                        <p class="text-center text-sm text-gray-600">PV : <?= $pokemon['hp'] ?></p>
                        <p class="text-center text-sm text-gray-600">Attaque : <?= $pokemon['attack'] ?></p>
                        <p class="text-center text-sm text-gray-600">Défense : <?= $pokemon['defense'] ?></p>
                    </label>
                <?php endforeach; ?>
            </div>

            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-6" type="submit">
                Démarrer le combat
            </button>
        </form>
    </div>

    <script>
        // Ajouter la classe "selected" pour les Pokémon sélectionnés
        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', () => {
                input.parentElement.parentElement.querySelectorAll('.pokemon-card').forEach(card => {
                    card.classList.remove('selected');
                });
                input.parentElement.classList.add('selected');
            });
        });
    </script>
</body>

</html>