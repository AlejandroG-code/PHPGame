<?php
// Game configuration
$config = [
    'canvas_width' => 1200,
    'canvas_height' => 700,
    'player_speed' => 200,
    'player_size' => 28,
    'player_health' => 10,
    'bullet_speed' => 550,
    'enemy_bullet_speed' => 160,
    'max_rooms' => 200
];

// Include PHP data files
include __DIR__ . '/php/data/attacks.php';
include __DIR__ . '/php/data/enemies.php';
include __DIR__ . '/php/data/world.php';
include __DIR__ . '/php/data/skills.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bullet Hell - Survival Mode</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <canvas id="game"></canvas>
    
    <!-- HUD -->
    <div id="hud-container"></div>
    
    <!-- Screens -->
    <div id="screens-container"></div>
    
    <!-- PHP Config -->
    <?php include __DIR__ . '/php/includes/client_config.php'; ?>
    
    <!-- PHP Data -->
    <?php include __DIR__ . '/php/includes/client_data.php'; ?>
    
    <!-- Core JS -->
    <script src="js/rendering/sprites.js"></script>
    <script src="js/rendering/effects.js"></script>
    <script src="js/rendering/particles.js"></script>
    <script src="js/ui/hud.js"></script>
    <script src="js/ui/screens.js"></script>
    <script src="js/core/game_state.js"></script>
    <script src="js/systems/spawn.js"></script>
    <script src="js/systems/world.js"></script>
    <script src="js/systems/skills.js"></script>
    <script src="js/input/controls.js"></script>
    <script src="js/core/game_loop.js"></script>
    
    <script>
        // Initialize HUD and screens
        document.getElementById('hud-container').innerHTML = hudHTML;
        document.getElementById('screens-container').innerHTML = screensHTML;
        
        // Start game when ready
        window.addEventListener('load', () => {
            console.log('Game loaded successfully');
        });
    </script>
</body>
</html>
