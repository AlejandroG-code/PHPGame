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

    // DEBUG: Ver qué room se está cargando
    console.log('🚪 Cargando Room:', roomNum);

    // SISTEMA DE BOSSES PROGRESIVO - Boss cada 10 habitaciones
    const bossInterval = 10;
    
    // Rest room removida
    isRestRoom = false;
    
    // DEBUG: Ver si es room de boss
    const isBossRoom = roomNum % bossInterval === 0;
    console.log('🎲 Es room de boss?', isBossRoom, '(roomNum:', roomNum, '% bossInterval:', bossInterval, '=', roomNum % bossInterval, ')');
    
    // BOSS ROOM - Bosses progresivos (1-20)
    if (isBossRoom) {
        const bossNumber = Math.min(20, Math.floor(roomNum / bossInterval));
        
        // DEBUG: Verificar bosses
        console.log('🎯 Room:', roomNum, '| Boss Number:', bossNumber);
        console.log('📦 BOSSES disponibles:', typeof BOSSES !== 'undefined' ? Object.keys(BOSSES) : 'UNDEFINED');
        
        // Intentar acceder al boss de múltiples formas (por si las claves son strings)
        let bossData = null;
        if (typeof BOSSES !== 'undefined') {
            bossData = BOSSES[bossNumber] || BOSSES[String(bossNumber)] || null;
        }
        
        console.log('👑 Boss cargado:', bossData ? bossData.name : 'NULL - usando fallback');
        if (!bossData) {
            console.warn('⚠️ No se pudo cargar el boss', bossNumber, '- Claves disponibles:', typeof BOSSES !== 'undefined' ? Object.keys(BOSSES) : 'undefined');
        }
        
        // EFECTO ESPECIAL PARA BOSS FINAL
        if (bossNumber === 20) {
            if (typeof triggerChromatic === 'function') triggerChromatic(1.5, 3);
            if (typeof shakeScreen === 'function') shakeScreen(15, 2);
            console.log('%c⚡ BOSS FINAL: THE ABSOLUTE DEITY ⚡', 'color: #ff0000; font-size: 24px; font-weight: bold;');
        }
        
        if (bossData) {
            // Usar el boss específico del array
            const bossHp = bossData.hp + Math.floor(roomNum/30) * 20; // Escala gradual
            
            // LOG PROMINENTE
            console.log('%c════════════════════════════════════════', 'color: #f39c12; font-weight: bold;');
            console.log('%c👑 BOSS CARGADO:', 'color: #e74c3c; font-size: 16px; font-weight: bold;');
            console.log('%c   Nombre: ' + bossData.name, 'color: #3498db; font-size: 14px;');
            console.log('%c   Título: ' + bossData.title, 'color: #2ecc71; font-size: 12px;');
            console.log('%c   Tier: ' + bossData.tier + ' | HP: ' + bossHp, 'color: #9b59b6; font-size: 12px;');
            console.log('%c════════════════════════════════════════', 'color: #f39c12; font-weight: bold;');
            
            enemies.push({
                x: CONFIG.CANVAS_W/2,
                y: CONFIG.CANVAS_H/2 - 20,
                type: bossData,
                hp: bossHp,
                maxHp: bossHp,
                shootTimer: bossData.shootInterval || 1.0,
                phaseTimer: 2.0,
                phase: 0,
                spiralAngle: 0,
                moveDir: 1,
                bossNumber: bossNumber,
                name: bossData.name,
                title: bossData.title,
                tier: bossData.tier,
                currentPhaseIndex: 0,
                mechanics: bossData.mechanics || [],
                hasResurrected: false
            });
        } else {
            // Fallback al boss genérico si no existe
            const bossHp = ENEMY_TYPES.BOSS.hp + Math.floor(roomNum/10) * 30;
            enemies.push({
                x: CONFIG.CANVAS_W/2,
                y: CONFIG.CANVAS_H/2 - 20,
                type: ENEMY_TYPES.BOSS,
                hp: bossHp,
                maxHp: bossHp,
                shootTimer: 1.0,
                phaseTimer: 3.0,
                phase: 3,
                spiralAngle: 0,
                moveDir: 1
            });
        }
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
