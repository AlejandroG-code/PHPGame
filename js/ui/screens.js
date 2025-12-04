// Screen HTML markup
const screensHTML = `
<!-- Menu Screen -->
<div id="menuScreen" class="screen active">
    <h1>🎮 BULLET HELL 🎮</h1>
    <p>WASD - Mover tu personaje</p>
    <p>Flechas - Disparar</p>
    <p>Elimina todos los enemigos para abrir la puerta</p>
    <button onclick="startGame()">INICIAR JUEGO</button>
</div>

<!-- Death Screen -->
<div id="deathScreen" class="screen">
    <h2 id="deathTitle">💀 GAME OVER 💀</h2>
    <div class="stats-final">
        <p>Habitación alcanzada: <strong id="finalRoom">0</strong></p>
        <p>Enemigos eliminados: <strong id="finalKills">0</strong></p>
    <div style="margin-top:20px; display:flex; justify-content:center; gap:20px;">
        <button onclick="restartGame()">REINTENTAR</button>
        <button onclick="backToMenu()" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);">MENÚ</button>
    </div></div>
</div>

<!-- Victory Screen -->
<div id="victoryScreen" class="screen" style="display:none;">
    <h1 style="color: #f1c40f; text-shadow: 0 0 20px rgba(241, 196, 15, 0.8);">👑 ¡VICTORIA TOTAL! 👑</h1>
    <p style="font-size: 18px; margin: 20px 0;">¡Has completado todas las habitaciones!</p>
    <div class="stats-final">
        <p>Habitación final: <strong id="victoryRoom">200</strong></p>
        <p>Total de kills: <strong id="victoryKills">0</strong></p>
    </div>
    <div style="margin-top:30px;">
        <button onclick="backFromVictory()">MENÚ</button>
    </div>
</div>
`;
