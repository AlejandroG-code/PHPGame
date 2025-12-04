// World-level updates and rendering moved out of game.php
// Expects globals: ctx, CONFIG, hazards, enemies, bullets, enemyBullets, player, ENEMY_TYPES, roomCleared

function updateWorld(dt) {
    // Update room transition fade effect (primary handler)
    if (typeof roomTransition === 'number' && roomTransition > 0) {
        roomTransition = Math.max(0, roomTransition - dt * 2); // Fade over ~0.5s
    }

    // Update hazards (webs, spores, fire pools, coils, traps, etc.) and apply effects if player stands on them
    for (let h = hazards.length - 1; h >= 0; h--) {
        const hazard = hazards[h];
        
        // Duración (solo expirar si la duración es finita)
        if (typeof hazard.duration === 'number' && isFinite(hazard.duration)) {
            hazard.duration -= dt;
            if (hazard.duration <= 0) { hazards.splice(h, 1); continue; }
        }

        // Movimiento y telegrafía para trampas móviles en salas de boss
        if (hazard.type === 'trap' && hazard.cx != null && hazard.cy != null && hazard.rx != null && hazard.ry != null && hazard.step) {
            // Temporizador base del ciclo
            if (typeof hazard.timer !== 'number') hazard.timer = 2.5;
            hazard.timer -= dt;

            // Fase de telegrafía: muestra siguiente posición antes de moverse
            if (hazard.telegraph && hazard.telegraph > 0) {
                hazard.telegraph -= dt;
                if (hazard.telegraph <= 0) {
                    // Mover a la posición telegrafiada
                    if (typeof hazard._nextAngle === 'number') hazard.angle = hazard._nextAngle;
                    if (typeof hazard.nextX === 'number') hazard.x = hazard.nextX;
                    if (typeof hazard.nextY === 'number') hazard.y = hazard.nextY;
                    hazard.nextX = hazard.nextY = null;
                    hazard._nextAngle = null;
                }
            } else if (hazard.timer <= 0) {
                // Elegir siguiente punto en el anillo y empezar telegrafía
                const nextAngle = hazard.angle + hazard.step;
                hazard._nextAngle = nextAngle;
                hazard.nextX = hazard.cx + Math.cos(nextAngle) * hazard.rx;
                hazard.nextY = hazard.cy + Math.sin(nextAngle) * hazard.ry;
                hazard.telegraph = 0.85; // avisar ~0.85s antes de moverse
                hazard.timer = 2.6 + Math.random() * 0.8; // intervalo entre movimientos
            }
        }

    // Precisa: colisión círculo-rectángulo para detectar solape con el jugador
    const overlapped = circleRect(hazard.x, hazard.y, (hazard.r || 30), player);
    if (overlapped) {
            // WEB - Ralentiza
            if (hazard.type === 'web') {
                player.effects.slowTimer = Math.max(player.effects.slowTimer, 0.25);
                player.effects.slowAmount = Math.max(player.effects.slowAmount || 0, hazard.slowAmount || 0.5);
            }

            // SPORE - Veneno persistente
            if (hazard.type === 'spore') {
                player.effects.poisonTimer = Math.max(player.effects.poisonTimer, hazard.dotDuration || 3);
                player.effects.poisonTickInterval = hazard.tick || 0.5;
                player.effects.poisonTick = player.effects.poisonTickInterval;
            }

            // FIRE - Daño por quemadura
            if (hazard.type === 'fire') {
                if (!hazard.lastTick) hazard.lastTick = 0;
                hazard.lastTick -= dt;
                if (hazard.lastTick <= 0) {
                    player.health = Math.max(0, player.health - (hazard.damage || 1));
                    hazard.lastTick = hazard.tick || 0.5;
                    // También aplica efecto DoT
                    if (hazard.dotDuration) {
                        player.effects.poisonTimer = Math.max(player.effects.poisonTimer, hazard.dotDuration);
                        player.effects.poisonTickInterval = 0.5;
                    }
                }
            }

            // COIL - Trampa de serpiente que ralentiza mucho
            if (hazard.type === 'coil') {
                player.effects.slowTimer = Math.max(player.effects.slowTimer, 0.5);
                player.effects.slowAmount = Math.max(player.effects.slowAmount || 0, hazard.slowAmount || 0.8);
            }
            // TRAP - Daño periódico mientras se pisa
            if (hazard.type === 'trap') {
                if (!hazard.tick) hazard.tick = 0;
                hazard.tick -= dt;
                if (hazard.tick <= 0) {
                    player.health = Math.max(0, player.health - (hazard.damage || 1));
                    hazard.tick = 0.6; // cada 0.6s
                }
            }
        }
    }
}
