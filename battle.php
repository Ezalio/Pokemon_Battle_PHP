<?php
require_once 'classes/Pokemon.php';
require_once 'classes/PokemonTypes.php';
require_once 'classes/Combat.php';

session_start();

// Vérifier si le jeu est terminé et afficher le résultat
if (isset($_GET['gameOver']) && isset($_SESSION['winner'])) {
    $winnerMessage = $_SESSION['winner'];
    $gameOver = true;
} elseif (!isset($_SESSION['combat'])) {
    die("Erreur : La session de combat n'a pas été initialisée.");
}

// Récupérer les données du combat
$combat = $_SESSION['combat'] ?? null;
$combatLog = $_SESSION['combatLog'] ?? [];
$gameOver = $gameOver ?? false;

// Tour du joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_SESSION['playerTurn']) {
    $action = intval($_POST['action']);

    // Traiter l'action du joueur
    $combatLog[] = $combat->processPlayerAction($action, $combat->getPokemon1(), $combat->getPokemon2());

    // Vérifier si l'adversaire est KO
    if ($combat->getPokemon2()->estKO()) {
        $gameOver = true;
        $combatLog[] = $combat->determinerVainqueur();
        $_SESSION['winner'] = $combat->getPokemon1()->getNom();
        $_SESSION['combatLog'] = $combatLog;
        session_write_close(); // Sauvegarder les données de la session pour l'état "jeu terminé"
        echo json_encode(["success" => true, "gameOver" => $gameOver]);
        exit();
    } else {
        // Passer le tour à l'adversaire
        $_SESSION['playerTurn'] = false;
        $_SESSION['combatLog'] = $combatLog;
    }

    // Retourner le succès de l'action du joueur
    echo json_encode(["success" => true]);
    exit();
}

// Tour de l'adversaire
if (!$_SESSION['playerTurn'] && !$gameOver) {
    $combatLog[] = $combat->processOpponentAction($combat->getPokemon2(), $combat->getPokemon1());

    // Vérifier si le Pokémon du joueur est KO
    if ($combat->getPokemon1()->estKO()) {
        $gameOver = true;
        $combatLog[] = $combat->determinerVainqueur();
        $_SESSION['winner'] = $combat->getPokemon2()->getNom();
        $_SESSION['combatLog'] = $combatLog;
        session_write_close();
        header("Location: battle.php?gameOver=true");
        exit();
    } else {
        // Repasser le tour au joueur
        $_SESSION['playerTurn'] = true;
        $_SESSION['combatLog'] = $combatLog;
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combat Pokémon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="animations.css">
    <script>
        async function handlePlayerAction(action) {
            // Déclencher une animation en fonction de l'action du joueur
            if (action === 1 || action === 2) {
                triggerAnimation('#pokemon1', 'attack-animation-player');
                triggerAnimation('#pokemon2', 'damage-animation');
            } else if (action === 3) {
                triggerAnimation('#pokemon1', 'heal-animation');
            }

            // Envoyer l'action du joueur au serveur
            const response = await fetch("battle.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=${action}`
            });
            
            // Gérer la réponse du serveur
            const result = await response.json();
            if (result.success) {
                // Attendre un délai pour le tour de l'ordinateur
                setTimeout(() => {
                    triggerAnimation('#pokemon2', 'attack-animation-opponent');
                    triggerAnimation('#pokemon1', 'damage-animation');
                    window.location.reload();
                }, 1000);
            }
        }

        // Déclencher une animation spécifique sur un élément
        function triggerAnimation(target, animationClass) {
            const element = document.querySelector(target);
            element.classList.add(animationClass);
            setTimeout(() => {
                element.classList.remove(animationClass);
            }, 1000);
        }
    </script>
</head>

<body class="bg-gray-200">
    <div class="container mx-auto p-6">
        <div id="game-over-message" style="display: none;" class="text-center text-red-600 text-xl font-bold"></div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Pokémon de l'adversaire -->
            <div class="flex flex-col items-center sm:pl-96 pl-16">
                <h3 class="font-bold text-lg"><?= $combat->getPokemon2()->getNom() ?></h3>
                <div class="hp-bar mt-2">
                    <div class="hp-bar-inner"
                        style="width: <?= ($combat->getPokemon2()->getPointsDeVie() / $combat->getPokemon2()->getMaxPointsDeVie()) * 100 ?>%;"></div>
                </div>
                <p class="text-sm">PV : <?= $combat->getPokemon2()->getPointsDeVie() ?>/<?= $combat->getPokemon2()->getMaxPointsDeVie() ?></p>
                <?php if (!isset($_SESSION['winner']) || $_SESSION['winner'] == $combat->getPokemon2()->getNom()): ?>
                    <img id="pokemon2" src="images/<?= strtolower($combat->getPokemon2()->getNom()) ?>.png" alt="<?= $combat->getPokemon2()->getNom() ?>" class="w-32 h-32">
                <?php else: ?>
                    <div class="w-32 h-32"></div>
                <?php endif; ?>
            </div>

            <!-- Pokémon du joueur -->
            <div class="flex flex-col items-center mt-12 sm:pr-96 pr-16">
                <?php if (!isset($_SESSION['winner']) || $_SESSION['winner'] == $combat->getPokemon1()->getNom()): ?>
                <img id="pokemon1" src="images/<?= strtolower($combat->getPokemon1()->getNom()) ?>.png" alt="<?= $combat->getPokemon1()->getNom() ?>" class="w-32 h-32 flip-horizontal">
                <?php else: ?>
                    <div class="w-32 h-32"></div>
                <?php endif; ?>
                <h3 class="font-bold text-lg mt-2"><?= $combat->getPokemon1()->getNom() ?></h3>
                <div class="hp-bar mt-2">
                    <div class="hp-bar-inner"
                        style="width: <?= ($combat->getPokemon1()->getPointsDeVie() / $combat->getPokemon1()->getMaxPointsDeVie()) * 100 ?>%;"></div>
                </div>
                <p class="text-sm">PV : <?= $combat->getPokemon1()->getPointsDeVie() ?>/<?= $combat->getPokemon1()->getMaxPointsDeVie() ?></p>
            </div>

            <!-- Journal de combat -->
            <div class="bg-gray-800 text-white min-h-20 p-4 mt-6 rounded">
                <p>
                    <?php
                    $lastActions = array_slice($combatLog, -2); // Afficher les deux dernières actions
                    foreach ($lastActions as $action) {
                        if ($action !== null) {
                            echo nl2br(htmlspecialchars($action)) . "<br>";
                        }
                    }
                    ?>
                </p>
            </div>

            <!-- Options du joueur -->
            <?php if ((!isset($_SESSION['winner'])) && $_SESSION['playerTurn']): ?>
                <div class="mt-4">
                    <div class="grid grid-cols-3 gap-4">
                        <button onclick="handlePlayerAction(1);" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Attaquer
                        </button>
                        <button onclick="handlePlayerAction(2);" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Attaque spéciale
                        </button>
                        <button onclick="handlePlayerAction(3);" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Soins
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Bouton arrêt -->
            <a href="form.php?reset=true" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">
                Arrêter le combat
            </a>
        </div>
    </div>
</body>
</html>
