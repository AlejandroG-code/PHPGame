<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =============================================================
//               Configuración del juego en PHP
// =============================================================
$config = [
    'canvas_width' => 1400,         // px - MUCHO MÁS GRANDE
    'canvas_height' => 700,         // px - MÁS ESPACIO VERTICAL
    'player_speed' => 280,          // px/s - MÁS RÁPIDO Y RESPONSIVO
    'player_size' => 40,            // px - HITBOX MÁS PEQUEÑA
    'player_health' => 50,         // HP - MÁS HEALTH PARA BALANCEAR
    'bullet_speed' => 550,          // px/s - BALAS MÁS RÁPIDAS
    'enemy_bullet_speed' => 160,    // px/s - ENEMIGOS MÁS LENTOS = ESQUIVABLE
    'max_rooms' => 100               // numero maximo de habitaciones antes de reiniciar
];

// incluir definiciones modulares (armas, ataques, enemigos, mundo)
include __DIR__ . '/guns.php';
include __DIR__ . '/attacks.php';
include __DIR__ . '/enemies.php';
include __DIR__ . '/world.php';
include __DIR__ . '/skills.php';
include __DIR__ . '/bosses.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        .screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d0a18 0%, #1a1825 50%, #2a2438 100%);
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: scale(0.95);
    }
    
    .screen::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 50%, rgba(243, 156, 18, 0.05) 0%, transparent 70%);
        animation: breathe 4s ease-in-out infinite;
    }
    
    @keyframes breathe {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.6; }
    }

    .screen.active {
        opacity: 1;
        pointer-events: all;
        transform: scale(1);
    }

    .screen h1 {
        font-size: 72px;
        margin: 0 0 20px 0;
        text-shadow: 
            0 0 20px rgba(243, 156, 18, 0.8),
            0 0 40px rgba(243, 156, 18, 0.5),
            0 0 80px rgba(243, 156, 18, 0.3);
        animation: pulse 2s ease-in-out infinite, titleGlow 3s ease-in-out infinite;
        background: linear-gradient(45deg, #f39c12, #e67e22, #f39c12);
        background-size: 200% 200%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    @keyframes titleGlow {
        0%, 100% { filter: brightness(1); }
        50% { filter: brightness(1.3); }
    }

    .screen h2 {
        font-size: 48px;
        margin: 0 0 30px 0;
        color: #e74c3c;
    }

    .screen p {
        font-size: 18px;
        margin: 10px 0;
        opacity: 0.8;
    }

    .screen button {
        margin-top: 30px;
        padding: 15px 40px;
        font-size: 24px;
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        cursor: pointer;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 
            0 4px 15px rgba(243, 156, 18, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .screen button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .screen button:hover::before {
        width: 300px;
        height: 300px;
    }

    .screen button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 
            0 8px 25px rgba(243, 156, 18, 0.6),
            0 0 20px rgba(243, 156, 18, 0.4);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .screen button:active {
        transform: translateY(-2px) scale(1.02);
    }

    .stats-final {
        background: linear-gradient(135deg, rgba(26,24,37,0.9), rgba(15,15,26,0.95));
        padding: 24px 48px;
        border-radius: 16px;
        margin-top: 30px;
        border: 2px solid rgba(243, 156, 18, 0.3);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.8),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .stats-final p {
        font-size: 20px;
        margin: 12px 0;
        opacity: 1;
        animation: floatIn 0.6s ease-out backwards;
    }
    
    .stats-final p:nth-child(1) { animation-delay: 0.1s; }
    .stats-final p:nth-child(2) { animation-delay: 0.2s; }
    
    .stats-final strong {
        color: #f39c12;
        text-shadow: 0 0 10px rgba(243, 156, 18, 0.5);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes healthPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    @keyframes floatIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
        body { 
            margin:0; 
            background:#111; 
            color:#fff; 
            font-family:Arial; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            height:100vh; /* usar toda la ventana para centrar verticalmente */
            flex-direction:column;
        }
        /* contenedor para centrar el mundo (canvas) y mantener orden en el DOM */
        .game-wrap {
            display:flex;
            align-items:center;
            justify-content:center;
            width:100%;
            height:100%;
            pointer-events:none; /* dejar que overlays capturen clicks cuando activas */
        }
        .game-wrap > canvas { pointer-events:all; }
        canvas { 
            background:#222; 
            border:3px solid #555; 
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8), 0 0 60px rgba(41, 181, 246, 0.3);
            border-radius: 4px;
        }
        .info { 
            position:fixed; 
            top:8px; 
            left:8px; 
            background:rgba(0,0,0,0.7); 
            padding:8px 12px; 
            border-radius:4px; 
            font-size:13px; 
        }
        .stats {
            position:fixed;
            bottom:16px;
            left:16px;
            background:linear-gradient(135deg, rgba(26,24,37,0.85), rgba(15,15,26,0.9));
            padding:14px 16px;
            border-radius:12px;
            font-size:14px;
            line-height:1.4;
            color:#fff;
            min-width:180px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.7), inset 0 1px 1px rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(243, 156, 18, 0.3);
            z-index: 1100;
            animation: floatIn 0.5s ease-out;
        }
        .stats .stat-row{ display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
        .stats .stat-row:last-child{ margin-bottom:0; }
        .stats .label{ color:#bbb; font-size:12px; margin-right:8px; }
        .stats .value{ font-weight:700; color:#fff; min-width:40px; text-align:right; }
        .health-wrap{ display:flex; align-items:center; gap:8px; }
        .health-bar{ width:110px; height:12px; background:rgba(255,255,255,0.08); border-radius:6px; overflow:hidden; box-shadow: inset 0 -2px 6px rgba(0,0,0,0.6), 0 0 10px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); }
        .health-fill{ height:100%; width:100%; background:linear-gradient(90deg,#4CAF50,#66BB6A); transition:width 200ms ease-out, background 300ms ease; box-shadow: 0 0 8px rgba(76, 175, 80, 0.5); }
        .health-text{ font-weight:700; color:#fff; font-size:13px; min-width:36px; text-align:right; }
        
        /* Skill Bar HUD */
        .skill-bar {
            position: fixed;
            bottom: 16px;
            right: 16px;
            display: flex;
            gap: 10px;
            z-index: 1100;
        }
        .skill-slot {
            position: relative;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, rgba(26,24,37,0.9), rgba(15,15,26,0.95));
            border-radius: 10px;
            border: 2px solid rgba(243, 156, 18, 0.4);
            box-shadow: 0 4px 12px rgba(0,0,0,0.7), inset 0 1px 2px rgba(255,255,255,0.08);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            overflow: hidden;
        }
        .skill-slot:hover {
            transform: translateY(-2px);
            border-color: rgba(243, 156, 18, 0.7);
            box-shadow: 0 6px 16px rgba(0,0,0,0.8), inset 0 1px 2px rgba(255,255,255,0.12);
        }
        .skill-slot.ready {
            border-color: rgba(46, 204, 113, 0.8);
            box-shadow: 0 0 16px rgba(46, 204, 113, 0.4), 0 4px 12px rgba(0,0,0,0.7);
        }
        .skill-slot.active {
            border-color: rgba(52, 152, 219, 0.9);
            box-shadow: 0 0 20px rgba(52, 152, 219, 0.6), inset 0 0 12px rgba(52, 152, 219, 0.3);
            animation: skillPulse 0.8s ease-in-out infinite;
        }
        .skill-slot.empty {
            opacity: 0.4;
            border-color: rgba(127, 140, 141, 0.3);
        }
        .skill-key {
            position: absolute;
            top: 4px;
            left: 4px;
            font-size: 10px;
            font-weight: 700;
            color: #f39c12;
            text-shadow: 0 1px 2px rgba(0,0,0,0.8);
            z-index: 2;
        }
        .skill-icon {
            font-size: 22px;
            color: #ecf0f1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
            z-index: 1;
        }
        .skill-cooldown {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%;
            background: rgba(0,0,0,0.75);
            transition: height 0.1s linear;
            z-index: 3;
        }
        .skill-duration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
            z-index: 4;
            transition: width 0.1s linear;
        }
        @keyframes skillPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(52, 152, 219, 0.6), inset 0 0 12px rgba(52, 152, 219, 0.3); }
            50% { box-shadow: 0 0 28px rgba(52, 152, 219, 0.9), inset 0 0 16px rgba(52, 152, 219, 0.5); }
        }
        
        h1 {
            margin-bottom:10px;
            color:#f39c12;
        }
    </style>
</head>
<body>
    <div class="info">
        WASD: Mover | Flechas: Disparar lágrimas | Elimina enemigos para abrir puerta
    </div>
    <div class="game-wrap">
        <canvas id="game" width="<?php echo $config['canvas_width']; ?>" height="<?php echo $config['canvas_height']; ?>"></canvas>
    </div>
    <!-- HUD moved to ui.php -->

    <script>
    <?php include __DIR__ . '/client_config.php'; ?>

    // Exponer número máximo de habitaciones y frecuencia de cofres al cliente
    CONFIG.MAX_ROOMS = <?php echo (int)$config['max_rooms']; ?>;
    CONFIG.CHEST_INTERVAL = 3; // cada 3 habitaciones puede aparecer un cofre

    // Sanity defaults to avoid invisible entities if PHP config is missing
    CONFIG.CANVAS_W = Math.max(CONFIG.CANVAS_W || 0, 600);
    CONFIG.CANVAS_H = Math.max(CONFIG.CANVAS_H || 0, 400);
    CONFIG.PLAYER_SIZE = Math.max(CONFIG.PLAYER_SIZE || 0, 20);
    CONFIG.MAX_HEALTH = Math.max(CONFIG.MAX_HEALTH || 0, 100);

    var cvs = document.getElementById('game');
    var ctx = cvs.getContext('2d');

    // Fijar tamaño del canvas: usar los valores en CONFIG (ahora normalizados)
    cvs.width = CONFIG.CANVAS_W;
    cvs.height = CONFIG.CANVAS_H;
    // También fijar el tamaño visual (estilo) para evitar escalados por CSS
    cvs.style.width = CONFIG.CANVAS_W + 'px';
    cvs.style.height = CONFIG.CANVAS_H + 'px';

    // ===== ESTADO DEL JUEGO (variables globales sin let/const para acceso entre módulos) =====
    var player = { 
        x: CONFIG.CANVAS_W/2, 
        y: CONFIG.CANVAS_H-100, 
        w: CONFIG.PLAYER_SIZE, 
        h: CONFIG.PLAYER_SIZE, 
        health: CONFIG.MAX_HEALTH,
        shootCooldown: 0,
        effects: { poisonTimer: 0, poisonTick: 0, poisonTickInterval: 0, slowTimer: 0, slowAmount: 0 },
        facing: 'down' // 'up','down','left','right'
    };

    var keys = { left:false, right:false, up:false, down:false };
    var bullets = [];
    var enemies = [];
    var enemyBullets = [];
    // hazards: telarañas, zonas veneno, etc. (persisten en el mapa y afectan al jugador)
    var hazards = [];
    var currentRoom = 1;
    var roomCleared = false;
    var totalKills = 0;
    var gameStarted = false;
    var isRestRoom = false; // Sala de descanso antes del boss
    var roomTransition = 0; // 0-1, para fade in/out al cambiar habitación

    // ===== TIPOS DE ENEMIGOS / datos inyectados por PHP (ahora desde client_data.php) =====
    <?php include __DIR__ . '/client_data.php'; ?>

    // Usar la configuración del mundo para definir el borde del nivel
    CONFIG.BORDER = (typeof WORLD !== 'undefined' ? WORLD.BORDER : CONFIG.BORDER) || CONFIG.BORDER;

    // All game logic moved to modular client files
    // update() -> js/game_logic.js
    // draw() -> ui.php
    // controls -> js/controls.js
    // collision helpers -> js/utils.js
    // menu/state management -> js/game_state.js

    // ===== GAME LOOP =====
    var lastTime = performance.now();
    function loop(now) {
        var dt = (now - lastTime) / 1000;
        lastTime = now;
        
        if (gameStarted) {
            update(dt);
        }
        
        // Always draw, even when game hasn't started (shows background)
        if (typeof draw === 'function') {
            draw();
        }
        
        requestAnimationFrame(loop);
    }

    // Iniciar juego moved to after module includes so functions are available
    </script>

    <script src="js/utils.js"></script>
    <script src="js/weapons_client.js"></script>
    <script src="js/spawn_client.js"></script>
    <script src="js/enemies_client.js"></script>
    <script src="js/world_client.js"></script>
    <script src="js/controls.js"></script>
    <script src="js/game_state.js"></script>
    <script src="js/game_logic.js"></script>
    <script src="js/skills_client.js"></script>

    <?php include __DIR__ . '/ui.php'; ?>

    <script>
    // Initialize controls and start the game loop (loop will wait for gameStarted=true)
    if (typeof initControls === 'function') {
        initControls();
    }
    
    // Start the game loop (it will run but wait for gameStarted flag)
    requestAnimationFrame(loop);
    </script>
</body>
</html>