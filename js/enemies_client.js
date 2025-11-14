// Enemy-specific attack/behavior handler moved out of game.php
// Depends on global functions: shootAimed, shootBurst, shootSpiral, shootCircle, shootCone, shootRing
// Globals available: player, enemyBullets, hazards, CONFIG, enemies

function handleEnemyPattern(e, pattern, meta) {
    const name = meta.name || pattern;
    switch (name) {
        // ===== BASIC PATTERNS =====
        case 'aimed':
            shootAimed(e, player, meta.count || 1, meta.spread || 0.35, meta.speed || 220, {color: meta.color, shape: meta.shape});
            e.shootTimer = (meta.cooldown || 1.6) + Math.random() * 1.2;
            break;
        case 'burst':
            shootBurst(e, player, meta.waves || 3, {color: meta.color, shape: meta.shape});
            e.shootTimer = (meta.cooldown || 1.6) + Math.random() * 1.2;
            break;
        case 'spiral':
            shootSpiral(e, meta.bulletsPerWave || 6, meta.speed || 160, {color: meta.color, shape: meta.shape});
            e.shootTimer = 0.6 + Math.random() * 0.8;
            break;
        case 'circle':
            shootCircle(e, meta.count || 10, meta.speed || 120, {color: meta.color, shape: meta.shape});
            e.shootTimer = (meta.cooldown || 1.6) + Math.random() * 1.2;
            break;

        // ===== SLIME - División y dispersión =====
        case 'random':
            // Disparo aleatorio en todas direcciones
            for (let i = 0; i < (meta.count || 5); i++) {
                const angle = Math.random() * Math.PI * 2;
                const speed = (meta.speed || 140) + (Math.random() * 40 - 20);
                enemyBullets.push({
                    x: e.x, y: e.y, r: 4,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    color: meta.color,
                    shape: meta.shape || 'spark'
                });
            }
            e.shootTimer = 1.8 + Math.random() * 1.0;
            break;

        // ===== SPIDER - Telarañas y veneno =====
        case 'spider_white':
            // Create static webs that slow
            for (let w = 0; w < 1 + Math.floor(Math.random()*2); w++) {
                const angle = Math.random() * Math.PI * 2;
                const dist = e.type.size + 10 + Math.random()*20;
                hazards.push({
                    type: 'web',
                    x: e.x + Math.cos(angle) * dist,
                    y: e.y + Math.sin(angle) * dist,
                    r: 28,
                    slowAmount: meta.slowAmount || 0.6,
                    duration: 5.0
                });
            }
            e.shootTimer = 1.8 + Math.random() * 1.2;
            break;
        case 'spider_green':
            // Poison projectiles
            const optsSpider = {
                effect: 'poison',
                dotDuration: meta.dotDuration || 3,
                tick: meta.tick || 0.5
            };
            shootAimed(e, player, meta.count || 3, 0.25, meta.speed || 110, Object.assign({color: meta.color, shape: meta.shape || 'toxic'}, optsSpider));
            e.shootTimer = 1.6 + Math.random() * 1.0;
            break;

        // ===== SKELETON - Huesos que rebotan =====
        case 'bone_throw':
            shootAimed(e, player, meta.count || 3, 0.3, meta.speed || 180, {color: meta.color, shape: meta.shape});
            e.shootTimer = 1.6 + Math.random() * 1.0;
            break;

        // ===== EYE - Proyectiles que siguen =====
        case 'homing':
            // Balas que persiguen al jugador (implementación simplificada)
            for (let i = 0; i < (meta.count || 3); i++) {
                const angle = Math.atan2(player.y - e.y, player.x - e.x) + (Math.random() * 0.4 - 0.2);
                const speed = meta.speed || 150;
                enemyBullets.push({
                    x: e.x, y: e.y, r: 5,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    homing: true,
                    trackSpeed: meta.tracking || 0.05,
                    color: meta.color,
                    shape: meta.shape || 'orb_tail'
                });
            }
            e.shootTimer = 2.0 + Math.random() * 1.0;
            break;
        case 'eye_beam':
            // Rayo rápido directo
            const beamAngle = Math.atan2(player.y - e.y, player.x - e.x);
            enemyBullets.push({
                x: e.x, y: e.y, r: 6,
                vx: Math.cos(beamAngle) * (meta.speed || 280),
                vy: Math.sin(beamAngle) * (meta.speed || 280),
                color: meta.color || '#ff2d55',
                shape: meta.shape || 'beam_short'
            });
            e.shootTimer = 2.5 + Math.random() * 1.0;
            break;

        // ===== ICE - Congelación =====
        case 'ice_frag':
            const optsIce = {
                effect: 'slow',
                duration: meta.duration || 2.0,
                slowAmount: meta.slowAmount || 0.5
            };
            shootCircle(e, meta.count || 8, meta.speed || 130, Object.assign({color: meta.color, shape: meta.shape || 'shard'}, optsIce));
            e.shootTimer = 1.8 + Math.random() * 1.2;
            break;
        case 'freeze_nova':
            // Nova congelante masiva
            const optsFreeze = {
                effect: 'slow',
                duration: meta.duration || 1.0,
                slowAmount: 0.7
            };
            shootRing(e, meta.count || 12, meta.speed || 100, Object.assign({color: meta.color, shape: meta.shape || 'nova'}, optsFreeze));
            e.shootTimer = 3.0 + Math.random() * 1.5;
            break;

        // ===== FIRE - Piscinas de fuego =====
        case 'flame_wave':
            shootCone(e, player, meta.count || 8, meta.spread || 0.4, meta.speed || 160, {color: meta.color, shape: meta.shape || 'flame'});
            e.shootTimer = 1.4 + Math.random() * 1.0;
            break;
        case 'fire_pool':
            // Crea piscina de fuego en posición del enemigo
            hazards.push({
                type: 'fire',
                x: e.x,
                y: e.y,
                r: 35,
                damage: meta.damage || 1,
                tick: meta.tick || 0.5,
                duration: meta.duration || 4.0,
                dotDuration: 2.0,
                color: meta.color
            });
            e.shootTimer = 3.5 + Math.random() * 1.5;
            break;

        // ===== THUNDER - Cadenas eléctricas =====
        case 'chain_lightning':
            // Un rayo que podría "encadenar" (simplificado como rayo rápido)
            const lightningAngle = Math.atan2(player.y - e.y, player.x - e.x);
            enemyBullets.push({
                x: e.x, y: e.y, r: 4,
                vx: Math.cos(lightningAngle) * (meta.speed || 280),
                vy: Math.sin(lightningAngle) * (meta.speed || 280),
                color: meta.color,
                shape: meta.shape || 'zigzag'
            });
            e.shootTimer = 1.8 + Math.random() * 1.0;
            break;
        case 'thunder_storm':
            // Rayos aleatorios desde arriba
            for (let i = 0; i < (meta.count || 5); i++) {
                const randX = CONFIG.BORDER + Math.random() * (CONFIG.CANVAS_W - 2 * CONFIG.BORDER);
                enemyBullets.push({
                    x: randX,
                    y: CONFIG.BORDER,
                    r: 5,
                    vx: 0,
                    vy: meta.speed || 200,
                    color: meta.color,
                    shape: meta.shape || 'drop_light'
                });
            }
            e.shootTimer = 2.5 + Math.random() * 1.0;
            break;

        // ===== GHOST - Invulnerabilidad =====
        case 'ghost_blast':
            shootCircle(e, meta.count || 8, meta.speed || 140, {color: meta.color, shape: meta.shape || 'wisp'});
            e.shootTimer = 1.8 + Math.random() * 1.0;
            break;
        case 'shadow_invuln':
            // Occasionally become invulnerable briefly
            if (Math.random() < (meta.chance || 0.12)) {
                e.isInvuln = true;
                e.invulnTimer = meta.invulnDuration || 1.2;
            }
            e.shootTimer = (meta.cooldown || 1.6) + Math.random() * 1.2;
            break;

        // ===== ARMOR - Disparos pesados =====
        case 'heavy_shot':
            // Balas grandes y lentas
            for (let i = 0; i < (meta.count || 3); i++) {
                const angle = Math.atan2(player.y - e.y, player.x - e.x) + (i - 1) * 0.25;
                enemyBullets.push({
                    x: e.x, y: e.y, r: meta.size || 12,
                    vx: Math.cos(angle) * (meta.speed || 100),
                    vy: Math.sin(angle) * (meta.speed || 100),
                    pierce: meta.pierce || false,
                    color: meta.color,
                    shape: meta.shape || 'chunk'
                });
            }
            e.shootTimer = 2.5 + Math.random() * 1.0;
            break;
        case 'shield_bash':
            // Melee attack (no implementado como proyectil, pero marcamos cooldown)
            e.shootTimer = 3.0 + Math.random() * 1.0;
            break;

        // ===== MIMIC - Ataques sorpresa =====
        case 'bite':
            // Ataque melee (no genera proyectiles, solo cooldown)
            e.shootTimer = 2.0 + Math.random() * 1.0;
            break;
        case 'tongue_lash':
            shootAimed(e, player, meta.count || 4, 0.2, meta.speed || 220, {color: meta.color, shape: meta.shape || 'needle'});
            e.shootTimer = 1.5 + Math.random() * 1.0;
            break;

        // ===== GOBLIN - Lanzar objetos =====
        case 'throw':
            shootAimed(e, player, meta.count || 2, 0.3, meta.speed || 200, {color: meta.color, shape: meta.shape || 'lob'});
            e.shootTimer = 1.6 + Math.random() * 1.0;
            break;
        case 'dagger_rain':
            shootCone(e, player, meta.count || 6, meta.spread || 1.0, meta.speed || 180, {color: meta.color, shape: meta.shape || 'dagger'});
            e.shootTimer = 2.0 + Math.random() * 1.0;
            break;

        // ===== CULTIST - Invocación =====
        case 'summon':
            // Invocar un enemigo pequeño si no hay muchos
            if (enemies.length < 15 && Math.random() < 0.3) {
                const spawnType = ENEMY_TYPES[meta.spawn || 'SLIME'];
                if (spawnType) {
                    enemies.push({
                        type: spawnType,
                        x: e.x + (Math.random() * 60 - 30),
                        y: e.y + (Math.random() * 60 - 30),
                        hp: spawnType.hp,
                        vx: (Math.random() - 0.5) * 60,
                        vy: (Math.random() - 0.5) * 60,
                        shootTimer: 1.0 + Math.random(),
                        moveTimer: 1.0 + Math.random(),
                        moveSpeed: 40 + Math.random() * 30
                    });
                }
            }
            e.shootTimer = 4.0 + Math.random() * 2.0;
            break;
        case 'dark_ritual':
            shootSpiral(e, meta.count || 16, meta.speed || 120, {color: meta.color, shape: meta.shape || 'orb_dark'});
            e.shootTimer = 2.5 + Math.random() * 1.0;
            break;

        // ===== MUSHROOM - Esporas venenosas =====
        case 'spore':
            const optsSpore = {
                effect: 'poison',
                dotDuration: meta.dotDuration || 2,
                tick: meta.tick || 0.5
            };
            shootCircle(e, meta.count || 8, meta.speed || 80, Object.assign({color: meta.color, shape: meta.shape || 'spore'}, optsSpore));
            e.shootTimer = 1.5 + Math.random() * 1.0;
            break;
        case 'spore_cloud':
            // Nube de esporas venenosas
            hazards.push({
                type: 'spore',
                x: e.x,
                y: e.y,
                r: meta.radius || 40,
                duration: meta.duration || 5.0,
                effect: 'poison',
                dotDuration: 3,
                tick: 0.5,
                color: meta.color
            });
            e.shootTimer = 3.0 + Math.random() * 2.0;
            break;

        // ===== BABY_DRAGON - Aliento de fuego =====
        case 'fire_breath':
            shootCone(e, player, meta.count || 10, meta.cone || 0.6, meta.speed || 180, {color: meta.color, shape: meta.shape || 'cone_flame'});
            e.shootTimer = 1.8 + Math.random() * 1.0;
            break;
        case 'fireball':
            // Bola de fuego que "explota" (simplificado)
            const fireAngle = Math.atan2(player.y - e.y, player.x - e.x);
            enemyBullets.push({
                x: e.x, y: e.y, r: 10,
                vx: Math.cos(fireAngle) * (meta.speed || 160),
                vy: Math.sin(fireAngle) * (meta.speed || 160),
                color: meta.color,
                explosion: true,
                shape: meta.shape || 'ball_fire'
            });
            e.shootTimer = 2.5 + Math.random() * 1.5;
            break;

        // ===== BABY_GRIFFIN - Ataques aéreos =====
        case 'swoop':
            shootAimed(e, player, meta.count || 3, 0.2, meta.speed || 240, {color: meta.color, shape: meta.shape || 'slash'});
            e.shootTimer = 1.6 + Math.random() * 1.0;
            break;
        case 'beak_peck':
            // Ataque rápido melee
            e.shootTimer = 1.0 + Math.random() * 0.5;
            break;
        case 'talon_strike':
            shootCone(e, player, meta.count || 4, meta.spread || 0.8, meta.speed || 200, {color: meta.color, shape: meta.shape || 'slash'});
            e.shootTimer = 1.5 + Math.random() * 1.0;
            break;

        // ===== SNAKE - Veneno y trampas =====
        case 'venom_spit':
            const optsVenom = {
                effect: 'poison',
                dotDuration: meta.dotDuration || 4,
                tick: meta.tick || 0.5
            };
            shootAimed(e, player, meta.count || 2, 0.15, meta.speed || 160, Object.assign({color: meta.color, shape: meta.shape || 'toxic_drop'}, optsVenom));
            e.shootTimer = 1.4 + Math.random() * 1.0;
            break;
        case 'coil':
            // Crea trampa que ralentiza mucho
            hazards.push({
                type: 'coil',
                x: e.x,
                y: e.y,
                r: 25,
                slowAmount: meta.slow || 0.8,
                duration: meta.duration || 2.0,
                color: meta.color
            });
            e.shootTimer = 3.0 + Math.random() * 1.5;
            break;

        // ===== BOSS ATTACKS - ETAPA I: TERRITORIAL =====
        case 'slime_god_split':
        case 'slime_god_rain':
        case 'slime_god_bounce':
        case 'scaled_venom_burst':
        case 'scaled_coil_strike':
        case 'scaled_tail_sweep':
        case 'abyss_web_trap':
        case 'abyss_shadow_bolt':
        case 'abyss_cocoon':
        case 'runic_hammer_slam':
        case 'runic_shield_bash':
        case 'runic_explosion':
        case 'volcanic_eruption':
        case 'volcanic_meteor':
        case 'volcanic_lava_pool':
        // ===== BOSS ATTACKS - ETAPA II: BESTIA =====
        case 'griffin_dive_strike':
        case 'griffin_feather_storm':
        case 'griffin_aerial_sweep':
        case 'pyre_inferno_breath':
        case 'pyre_flame_spiral':
        case 'pyre_fire_rain':
        case 'trio_ice_fire_thunder':
        case 'trio_elemental_chaos':
        case 'trio_fusion_blast':
        case 'rune_forge_hammer':
        case 'rune_enchant_barrage':
        case 'rune_seal_explosion':
        case 'vampire_blood_drain':
        case 'vampire_bat_swarm':
        case 'vampire_dark_ritual':
        // ===== BOSS ATTACKS - ETAPA III: PLANETARIA =====
        case 'midas_golden_touch':
        case 'midas_treasure_burst':
        case 'midas_coin_rain':
        case 'illusion_clone_assault':
        case 'illusion_mirror_shatter':
        case 'illusion_void_prison':
        case 'chronos_time_stop':
        case 'chronos_temporal_rift':
        case 'chronos_age_decay':
        case 'phoenix_solar_flare':
        case 'phoenix_rebirth_nova':
        case 'phoenix_plasma_wings':
        case 'nexus_dimension_tear':
        case 'nexus_void_lance':
        case 'nexus_reality_collapse':
        // ===== BOSS ATTACKS - ETAPA IV: CÓSMICA =====
        case 'void_entropy_wave':
        case 'void_singularity':
        case 'void_unraveling':
        case 'wyrm_cosmic_breath':
        case 'wyrm_meteor_shower':
        case 'wyrm_planet_crush':
        case 'soul_essence_drain':
        case 'soul_spectral_army':
        case 'soul_life_transfer':
        case 'unbound_chaos_storm':
        case 'unbound_reality_break':
        case 'unbound_omnidirectional':
        case 'deity_divine_judgment':
        case 'deity_creation_destruction':
        case 'deity_infinite_cycle':
        case 'deity_apocalypse':
            // Todos los ataques de bosses usan sus metadatos específicos
            // Determinar tipo de patrón base
            const count = meta.count || 16;
            const speed = meta.speed || 180;
            const opts = {color: meta.color, shape: meta.shape};
            
            // Patrones según tipo
            if (meta.pattern === 'circle' || meta.pattern === 'burst' || meta.pattern === 'nova' || 
                meta.pattern === 'shockwave' || meta.pattern === 'seal' || meta.pattern === 'omni') {
                shootCircle(e, count, speed, opts);
            } else if (meta.pattern === 'spiral' || meta.pattern === 'double_spiral' || 
                       meta.pattern === 'slow_spiral' || meta.pattern === 'infinite') {
                shootSpiral(e, Math.floor(count / 2), speed, opts);
            } else if (meta.pattern === 'aimed' || meta.pattern === 'aimed_spread' || 
                       meta.pattern === 'barrage' || meta.pattern === 'clone_fire' ||
                       meta.pattern === 'judgment' || meta.pattern === 'lance') {
                shootAimed(e, player, count, 0.4, speed, opts);
            } else if (meta.pattern === 'cone' || meta.pattern === 'cone_wide' || 
                       meta.pattern === 'breath_cosmic' || meta.pattern === 'wings') {
                shootCone(e, player, count, 1.2, speed, opts);
            } else if (meta.pattern === 'burst_multi' || meta.pattern === 'tri_element') {
                shootBurst(e, player, Math.floor(count / 4), opts);
            } else {
                // Fallback a círculo
                shootCircle(e, count, speed, opts);
            }
            
            e.shootTimer = e.type.shootInterval || 1.0;
            break;

        // ===== FALLBACK =====
        default:
            // fallback
            shootCircle(e, meta.count || 8, meta.speed || 140, {color: meta.color || '#ffffff', shape: meta.shape || 'orb'});
            e.shootTimer = e.type.shootInterval || 1.6 + Math.random() * 1.2;
    }
}
