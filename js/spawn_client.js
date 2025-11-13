// spawn_client.js - room/enemy spawning (moved from game.php)
// Expects globals: ENEMY_TYPES, CONFIG, enemies, enemyBullets, player, hazards, isRestRoom, roomTransition

function loadRoom(roomNum) {
    // Start room transition
    roomTransition = 1.0;
    
    currentRoom = roomNum;
    enemies = [];
    enemyBullets = [];
    hazards = [];
    roomCleared = false;
    player.x = CONFIG.CANVAS_W/2;
    player.y = CONFIG.CANVAS_H - 100;

    // Boss every WORLD.BOSS_INTERVAL rooms (fallback 5)
    const bossInterval = (typeof WORLD !== 'undefined' && WORLD.BOSS_INTERVAL) ? WORLD.BOSS_INTERVAL : 5;
    
    // Rest room removida
    isRestRoom = false;
    
    // BOSS ROOM
    if (roomNum % bossInterval === 0) {
        // Elegir un boss de la lista BOSSES si está disponible, variar HP según sala
        let bossType = ENEMY_TYPES.BOSS;
        let bossMeta = null;
        if (typeof BOSSES !== 'undefined' && Object.keys(BOSSES).length > 0) {
            const keys = Object.keys(BOSSES);
            const pick = BOSSES[keys[Math.floor(Math.random() * keys.length)]];
            bossMeta = pick;
        }
        const baseHp = (bossMeta && bossMeta.base_hp) ? bossMeta.base_hp : ENEMY_TYPES.BOSS.hp;
        const bossHp = baseHp + Math.floor(roomNum/10) * 30;
        const bossSize = (bossMeta && bossMeta.size) ? bossMeta.size : ENEMY_TYPES.BOSS.size;
        const bossColor = (bossMeta && bossMeta.color) ? bossMeta.color : ENEMY_TYPES.BOSS.color;
        const bossTypeClone = Object.assign({}, ENEMY_TYPES.BOSS, { color: bossColor, size: bossSize, is_boss: true });
        enemies.push({
            x: CONFIG.CANVAS_W/2,
            y: CONFIG.CANVAS_H/2 - 20,
            type: bossTypeClone,
            hp: bossHp,
            maxHp: bossHp,
            shootTimer: 1.0,
            phaseTimer: 3.0,
            phase: 3,
            spiralAngle: 0,
            moveDir: 1
        });
        try { console.log('[BOSS SPAWN]', { room: roomNum, hp: bossHp, size: bossSize, color: bossColor, x: CONFIG.CANVAS_W/2, y: CONFIG.CANVAS_H/2 - 20 }); } catch (e) {}
        // Crear trampas alrededor del mapa (anillo) que se mueven y telegrafían su próxima posición
        const trapCount = 12;
        const cx = CONFIG.CANVAS_W/2;
        const cy = CONFIG.CANVAS_H/2;
        const radiusX = (CONFIG.CANVAS_W - 160)/2;
        const radiusY = (CONFIG.CANVAS_H - 160)/2;
        const step = (Math.PI * 2) / trapCount;
        for (let t = 0; t < trapCount; t++) {
            const ang = (t / trapCount) * Math.PI * 2;
            const x = cx + Math.cos(ang) * radiusX;
            const y = cy + Math.sin(ang) * radiusY;
            hazards.push({
                type: 'trap',
                x,
                y,
                r: 22,
                duration: 999,
                damage: 1,
                // metadata para movimiento
                cx, cy,
                rx: radiusX,
                ry: radiusY,
                angle: ang,
                step: step,
                timer: 2.2 + Math.random() * 1.2,
                telegraph: 0,
                nextX: null,
                nextY: null,
                _nextAngle: null
            });
        }
        try { console.log('[ROOM]', roomNum, '-> BOSS ROOM (interval', bossInterval, ')'); } catch (e) {}
        return;
    }

    const count = Math.min(1 + roomNum, 8);
    const types = [
        ENEMY_TYPES.SLIME, ENEMY_TYPES.SPIDER, ENEMY_TYPES.SKELETON, ENEMY_TYPES.EYE,
        ENEMY_TYPES.ICE, ENEMY_TYPES.FIRE, ENEMY_TYPES.THUNDER, ENEMY_TYPES.GHOST,
        ENEMY_TYPES.ARMOR, ENEMY_TYPES.MIMIC, ENEMY_TYPES.GOBLIN, ENEMY_TYPES.CULTIST,
        ENEMY_TYPES.MUSHROOM, ENEMY_TYPES.BABY_DRAGON, ENEMY_TYPES.BABY_GRIFFIN, ENEMY_TYPES.SNAKE
    ];

    // Mejor distribución espacial con grid y separación mínima
    const minSeparation = 100; // Distancia mínima entre enemigos
    const spawned = [];
    
    for (let i = 0; i < count; i++) {
        const type = types[Math.floor(Math.random() * types.length)];
        const speed = (type.size <= 16) ? 25 : (type.size >= 30 ? 12 : 20);
        const angle = Math.random() * Math.PI * 2;
        
        // Intentar encontrar posición válida (no sobrepuesta con otros enemigos)
        let validPos = false;
        let attempts = 0;
        let spawnX, spawnY;
        
        while (!validPos && attempts < 30) {
            spawnX = 100 + Math.random() * (CONFIG.CANVAS_W - 200);
            spawnY = 100 + Math.random() * (CONFIG.CANVAS_H - 200);
            
            // Evitar spawn cerca del jugador
            const distToPlayer = Math.hypot(spawnX - CONFIG.CANVAS_W/2, spawnY - (CONFIG.CANVAS_H - 100));
            if (distToPlayer < 150) {
                attempts++;
                continue;
            }
            
            // Verificar separación con enemigos ya spawneados
            validPos = true;
            for (let s of spawned) {
                const dist = Math.hypot(spawnX - s.x, spawnY - s.y);
                if (dist < minSeparation) {
                    validPos = false;
                    break;
                }
            }
            attempts++;
        }
        
        // Si no encontramos posición válida, usar posición random
        if (!validPos) {
            spawnX = 100 + Math.random() * (CONFIG.CANVAS_W - 200);
            spawnY = 100 + Math.random() * (CONFIG.CANVAS_H - 200);
        }
        
        const enemy = {
            x: spawnX,
            y: spawnY,
            type: type,
            hp: type.hp,
            maxHp: type.hp,
            shootTimer: Math.random() * type.shootInterval,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            moveTimer: 1 + Math.random() * 2,
            moveSpeed: speed,
            spiralAngle: Math.random() * Math.PI * 2
        };
        
        enemies.push(enemy);
        spawned.push(enemy);
    }

    // Generación de cofres cada CONFIG.CHEST_INTERVAL habitaciones (posición aleatoria)
    const chestInterval = (typeof CONFIG !== 'undefined' && CONFIG.CHEST_INTERVAL) ? CONFIG.CHEST_INTERVAL : 3;
    if (roomNum % chestInterval === 0) {
        // 80% cofre normal con skill, 20% mimic
        const isMimic = Math.random() < 0.20;
        const cx = 120 + Math.random() * (CONFIG.CANVAS_W - 240);
        const cy = 120 + Math.random() * (CONFIG.CANVAS_H - 240);
        if (isMimic) {
            // Spawn a mimic enemy at chest position
            const mimicType = ENEMY_TYPES.MIMIC || { color: '#d35400', size: 24, hp: 4 };
            enemies.push({ x: cx, y: cy, type: mimicType, hp: mimicType.hp, maxHp: mimicType.hp, shootTimer: 1.0, moveTimer: 1.0, moveSpeed: 30 });
        } else {
            // Represent a chest as a hazard of type 'chest' so UI can render it and player can open it
            hazards.push({ type: 'chest', x: cx, y: cy, r: 20, opened: false, containsSkill: true });
        }
    }
    try { console.log('[ROOM]', roomNum, '-> NORMAL ROOM'); } catch (e) {}
}
