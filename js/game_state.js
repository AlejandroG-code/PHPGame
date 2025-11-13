// game_state.js - Menu/death screens and game state management
// Expects globals: player, bullets, enemyBullets, totalKills, loadRoom, CONFIG, currentRoom, gameStarted

function resetGame() {
    player.health = CONFIG.MAX_HEALTH;
    player.x = CONFIG.CANVAS_W/2;
    player.y = CONFIG.CANVAS_H - 100;
    bullets = [];
    enemyBullets = [];
    totalKills = 0;
    loadRoom(1);
}

function startGame() {
    document.getElementById('menuScreen').classList.remove('active');
    gameStarted = true;
    resetGame();
}

function restartGame() {
    document.getElementById('deathScreen').classList.remove('active');
    gameStarted = true;
    resetGame();
}

function backToMenu() {
    document.getElementById('deathScreen').classList.remove('active');
    document.getElementById('menuScreen').classList.add('active');
    gameStarted = false;
}

function showDeathScreen() {
    document.getElementById('finalRoom').textContent = currentRoom;
    document.getElementById('finalKills').textContent = totalKills;
    document.getElementById('deathScreen').classList.add('active');
}

function showVictoryScreen() {
    // TODO: implement victory screen (for now use death screen)
    showDeathScreen();
}
