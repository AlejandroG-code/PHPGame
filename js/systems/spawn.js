// spawn_client.js - room/enemy spawning
// Expects globals: ENEMY_TYPES, CONFIG, enemies, enemyBullets, player, hazards, roomTransition

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

    console.log('🚪 Cargando Room:', roomNum);

    // Aumentar significativamente el número de enemigos
    const count = Math.min(3 + Math.floor(roomNum * 1.5), 15);
    const types = [
        ENEMY_TYPES.SLIME, ENEMY_TYPES.SPIDER, ENEMY_TYPES.SKELETON, ENEMY_TYPES.EYE,
        ENEMY_TYPES.ICE, ENEMY_TYPES.FIRE, ENEMY_TYPES.THUNDER, ENEMY_TYPES.GHOST,
        ENEMY_TYPES.ARMOR, ENEMY_TYPES.MIMIC, ENEMY_TYPES.GOBLIN, ENEMY_TYPES.CULTIST,
        ENEMY_TYPES.MUSHROOM, ENEMY_TYPES.BABY_DRAGON, ENEMY_TYPES.BABY_GRIFFIN, ENEMY_TYPES.SNAKE
    ];

    const minSeparation = 80;
    const spawned = [];
    
    for (let i = 0; i < count; i++) {
        const type = types[Math.floor(Math.random() * types.length)];
        const speed = (type.size <= 16) ? 25 : (type.size >= 30 ? 12 : 20);
        const angle = Math.random() * Math.PI * 2;
        
        let validPos = false;
        let attempts = 0;
        let spawnX, spawnY;
        
        while (!validPos && attempts < 30) {
            spawnX = 100 + Math.random() * (CONFIG.CANVAS_W - 200);
            spawnY = 100 + Math.random() * (CONFIG.CANVAS_H - 200);
            
            const distToPlayer = Math.hypot(spawnX - CONFIG.CANVAS_W/2, spawnY - (CONFIG.CANVAS_H - 100));
            if (distToPlayer < 150) {
                attempts++;
                continue;
            }
            
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

    // Cofres cada 3 habitaciones
    const chestInterval = 3;
    if (roomNum % chestInterval === 0) {
        const isMimic = Math.random() < 0.20;
        const cx = 120 + Math.random() * (CONFIG.CANVAS_W - 240);
        const cy = 120 + Math.random() * (CONFIG.CANVAS_H - 240);
        if (isMimic) {
            const mimicType = ENEMY_TYPES.MIMIC || { color: '#d35400', size: 24, hp: 4 };
            enemies.push({ x: cx, y: cy, type: mimicType, hp: mimicType.hp, maxHp: mimicType.hp, shootTimer: 1.0, moveTimer: 1.0, moveSpeed: 30 });
        } else {
            hazards.push({ type: 'chest', x: cx, y: cy, r: 20, opened: false, containsSkill: true });
        }
    }
    console.log('[ROOM]', roomNum, '-> NORMAL ROOM (', count, 'enemies)');
}
