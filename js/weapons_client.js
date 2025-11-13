// weapons_client.js - shooting helpers (moved out of game.php)
// Expects globals: enemyBullets

function shootCircle(enemy, count = 12, speed = CONFIG.ENEMY_BULLET_SPEED, opts = {}) {
    for (let i = 0; i < count; i++) {
        const angle = (i / count) * Math.PI * 2;
        const bullet = { x: enemy.x, y: enemy.y, vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed, r: 6 };
        Object.assign(bullet, opts);
        enemyBullets.push(bullet);
    }
}

function shootRing(enemy, count = 20, speed = 140, opts = {}) {
    shootCircle(enemy, count, speed, opts);
}

function shootSpiral(enemy, bulletsPerWave = 6, speed = 160, opts = {}) {
    enemy.spiralAngle = (enemy.spiralAngle || 0) + 0.4;
    for (let i = 0; i < bulletsPerWave; i++) {
        const angle = enemy.spiralAngle + (i / bulletsPerWave) * Math.PI * 2;
        const bullet = { x: enemy.x, y: enemy.y, vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed, r: 6 };
        Object.assign(bullet, opts);
        enemyBullets.push(bullet);
    }
}

function shootAimed(enemy, target, count = 1, spread = 0.3, speed = 300, opts = {}) {
    const dx = target.x + target.w/2 - enemy.x;
    const dy = target.y + target.h/2 - enemy.y;
    const base = Math.atan2(dy, dx);
    for (let i = 0; i < count; i++) {
        const angle = base + (i - (count-1)/2) * spread;
        const bullet = { x: enemy.x, y: enemy.y, vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed, r: 6 };
        Object.assign(bullet, opts);
        enemyBullets.push(bullet);
    }
}

function shootBurst(enemy, target, waves = 3, opts = {}) {
    for (let w = 0; w < waves; w++) {
        const count = 8;
        const spread = 0.8;
        setTimeout(() => {
            shootAimed(enemy, target, count, spread, 200 + w*30, opts);
        }, w * 120);
    }
}

function shootCone(enemy, target, count = 5, arc = 1.0, speed = 260, opts = {}) {
    const dx = target.x + target.w/2 - enemy.x;
    const dy = target.y + target.h/2 - enemy.y;
    const base = Math.atan2(dy, dx);
    for (let i = 0; i < count; i++) {
        const t = (i / (count - 1)) - 0.5;
        const angle = base + t * arc;
        const bullet = { x: enemy.x, y: enemy.y, vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed, r: 6 };
        Object.assign(bullet, opts);
        enemyBullets.push(bullet);
    }
}
