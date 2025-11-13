// game_logic.js - Core game update loop and logic (moved from game.php)
// Expects globals: player, bullets, enemies, enemyBullets, hazards, keys, CONFIG, ENEMY_TYPES, ATTACKS, currentRoom, roomCleared, totalKills

function update(dt) {
    // Pausar el juego si está activo el overlay de selección de skills
    if (typeof window !== 'undefined' && window.skillOverlayActive) {
        updateUI();
        return;
    }
    
    // Aplicar slow motion si está activo
    if (typeof updateSlowMo === 'function') {
        updateSlowMo(dt);
        if (window.slowMotion && window.slowMotion.active) {
            dt *= window.slowMotion.timeScale;
        }
    }
    
    // Actualizar screen shake
    if (typeof updateScreenShake === 'function') {
        updateScreenShake(dt);
    }
    
    // Actualizar player trail
    if (typeof updatePlayerTrail === 'function') {
        updatePlayerTrail(dt);
        // Añadir trail cada pocos frames si el jugador se está moviendo
        const moving = keys.left || keys.right || keys.up || keys.down;
        if (moving && Math.random() < 0.3) {
            addPlayerTrail();
        }
    }
    
    // Time stop congela enemigos y balas
    const timeStopped = (typeof window !== 'undefined' && window.timeStopTimer > 0);
    
    // Actualizar skills
    if (typeof updateSkills === 'function') {
        updateSkills(dt);
    }
    
    if (player.shootCooldown > 0) {
        const fireRateMult = player.fireRateMult || 1;
        player.shootCooldown = Math.max(0, player.shootCooldown - dt * (1 / fireRateMult));
    }

    // Procesar efectos sobre el jugador (poison/slow)
    if (player.effects.poisonTimer > 0) {
        player.effects.poisonTimer = Math.max(0, player.effects.poisonTimer - dt);
        player.effects.poisonTick = Math.max(0, (player.effects.poisonTick || player.effects.poisonTickInterval) - dt);
        if (player.effects.poisonTick <= 0) {
            player.health = Math.max(0, player.health - 1);
            player.effects.poisonTick = player.effects.poisonTickInterval || 0.5;
            if (player.health <= 0) { showDeathScreen(); return; }
        }
    }
    if (player.effects.slowTimer > 0) {
        player.effects.slowTimer = Math.max(0, player.effects.slowTimer - dt);
        if (player.effects.slowTimer === 0) player.effects.slowAmount = 0;
    }

    // Movimiento del jugador con aceleración suave (SMOOTH CONTROLS)
    let mx = (keys.right ? 1 : 0) - (keys.left ? 1 : 0);
    let my = (keys.down ? 1 : 0) - (keys.up ? 1 : 0);

    // Actualizar facing del jugador según input: preferir vertical si hay movimiento vertical, sino horizontal
    if (my < 0) player.facing = 'up';
    else if (my > 0) player.facing = 'down';
    else if (mx < 0) player.facing = 'left';
    else if (mx > 0) player.facing = 'right';

    // Inicializar velocidad si no existe
    if (!player.vx) player.vx = 0;
    if (!player.vy) player.vy = 0;

    if (mx !== 0 || my !== 0) {
        const mag = Math.sqrt(mx*mx + my*my);
        let speedMult = 1;
        
        // Aplicar modificadores de velocidad
        if (player.effects.slowTimer > 0) {
            speedMult *= (1 - (player.effects.slowAmount || 0.4));
        }
        if (player.passiveSpeedMult) speedMult *= player.passiveSpeedMult;
        if (player.effects.rageSpeedMult) speedMult *= player.effects.rageSpeedMult;
        if (player.effects.invisSpeedMult) speedMult *= player.effects.invisSpeedMult;
        if (player.effects.dashTimer > 0 && player.effects.dashSpeed) {
            speedMult *= player.effects.dashSpeed;
        }
        
        // Aceleración suave para movimiento más fluido
        const accel = 20; // Aceleración instantánea más alta
        const targetVx = (mx / mag) * CONFIG.PLAYER_SPEED * speedMult;
        const targetVy = (my / mag) * CONFIG.PLAYER_SPEED * speedMult;
        
        player.vx += (targetVx - player.vx) * Math.min(1, accel * dt);
        player.vy += (targetVy - player.vy) * Math.min(1, accel * dt);
        
        player.x += player.vx * dt;
        player.y += player.vy * dt;
    } else {
        // Fricción cuando no hay input (decelera suave)
        const friction = 12;
        player.vx *= Math.max(0, 1 - friction * dt);
        player.vy *= Math.max(0, 1 - friction * dt);
        
        player.x += player.vx * dt;
        player.y += player.vy * dt;
    }

    // World updates (hazards, world-level effects)
    if (typeof updateWorld === 'function') {
        updateWorld(dt);
    }
    
    // Actualizar partículas
    if (typeof updateParticles === 'function') {
        updateParticles(dt);
    }

    // Límites contra bordes
    player.x = Math.max(CONFIG.BORDER, Math.min(player.x, CONFIG.CANVAS_W - player.w - CONFIG.BORDER));
    player.y = Math.max(CONFIG.BORDER, Math.min(player.y, CONFIG.CANVAS_H - player.h - CONFIG.BORDER));

    // Actualizar balas del jugador
    for (let i = bullets.length - 1; i >= 0; i--) {
        const b = bullets[i];
        b.x += b.vx * dt;
        b.y += b.vy * dt;

        if (b.x < -20 || b.x > CONFIG.CANVAS_W + 20 || 
            b.y < -20 || b.y > CONFIG.CANVAS_H + 20) {
            bullets.splice(i, 1);
            continue;
        }

        // Colisión con enemigos
        for (let j = enemies.length - 1; j >= 0; j--) {
            const e = enemies[j];
            if (circleCircle(b.x, b.y, b.r, e.x, e.y, e.type.size/2)) {
                if (e.isInvuln) {
                    bullets.splice(i, 1);
                    break;
                }
                const bulletDamage = b.damage || 1;
                e.hp -= bulletDamage;
                bullets.splice(i, 1);
                if (e.hp <= 0) {
                    // Crear partículas de explosión
                    if (typeof createExplosion === 'function') {
                        createExplosion(e.x, e.y, e.type.color, e.type.size);
                    }
                    
                    // EFECTOS VISUALES AL MATAR
                    const isBoss = e.type.is_boss;
                    if (isBoss) {
                        // Boss kill: screen shake grande + slow mo intenso
                        if (typeof shakeScreen === 'function') shakeScreen(20, 0.5);
                        if (typeof triggerSlowMo === 'function') triggerSlowMo(1.2, 0.3);
                    } else {
                        // Enemy normal: shake pequeño + slow mo corto
                        if (typeof shakeScreen === 'function') shakeScreen(4, 0.15);
                        if (typeof triggerSlowMo === 'function') triggerSlowMo(0.15, 0.6);
                    }
                    
                    enemies.splice(j, 1);
                    totalKills++;
                    if (typeof checkSkillMilestone === 'function') {
                        checkSkillMilestone();
                    }
                }
                break;
            }
        }
    }

    // Actualizar enemigos (congelar si time stop activo)
    if (!timeStopped) {
    for (let i = enemies.length - 1; i >= 0; i--) {
        const e = enemies[i];

        // Movimiento para enemigos NO-boss
        if (!e.type || !e.type.is_boss) {
            e.moveTimer -= dt;
            e.x += e.vx * dt + Math.sin((performance.now()/1000) + i) * 6 * dt;
            e.y += e.vy * dt + Math.cos((performance.now()/1000) + i) * 6 * dt;

            if (e.x < 60 || e.x > CONFIG.CANVAS_W - 60) e.vx *= -1;
            if (e.y < 60 || e.y > CONFIG.CANVAS_H - 60) e.vy *= -1;

            if (e.moveTimer <= 0) {
                const toPlayerX = player.x - e.x + (Math.random()*100 - 50);
                const toPlayerY = player.y - e.y + (Math.random()*100 - 50);
                const mag = Math.sqrt(toPlayerX*toPlayerX + toPlayerY*toPlayerY) || 1;
                const speed = e.moveSpeed;
                e.vx = (toPlayerX / mag) * speed;
                e.vy = (toPlayerY / mag) * speed;
                e.moveTimer = 1 + Math.random() * 2;
            }
            if (e.isInvuln) {
                e.invulnTimer = Math.max(0, (e.invulnTimer || 0) - dt);
                if (e.invulnTimer <= 0) {
                    e.isInvuln = false;
                }
            }
        } else {
            // Boss behavior
            e.phaseTimer -= dt;
            e.shootTimer -= dt;
            
            const toPlayerX = player.x - e.x;
            const toPlayerY = player.y - e.y;
            const distToPlayer = Math.sqrt(toPlayerX*toPlayerX + toPlayerY*toPlayerY);
            const mag = distToPlayer || 1;
            
            // BOSS REMASTERIZADO - 4 FASES BASADAS EN HP
            const hpPercent = e.hp / e.maxHp;
            let currentPhase = 1;
            if (hpPercent <= 0.25) currentPhase = 4; // ENRAGE
            else if (hpPercent <= 0.5) currentPhase = 3;
            else if (hpPercent <= 0.75) currentPhase = 2;
            
            // Guardar fase para rendering
            e.visualPhase = currentPhase;
            
            // Movimiento del boss - MÁS PREDECIBLE pero dinámico
            const moveSpeed = 40 + (currentPhase * 8); // Aumenta velocidad con fase
            e.x += (toPlayerX / mag) * moveSpeed * dt;
            e.y += (toPlayerY / mag) * (moveSpeed * 0.7) * dt + Math.sin(performance.now() / 600 + e.x) * 30 * dt;
            
            const margin = 120;
            e.x = Math.max(margin, Math.min(e.x, CONFIG.CANVAS_W - margin));
            e.y = Math.max(margin, Math.min(e.y, CONFIG.CANVAS_H - margin));
            
            // Telegraph de siguiente ataque (indicador visual)
            if (!e.nextAttackTelegraph) e.nextAttackTelegraph = 0;
            if (e.shootTimer < 0.5 && e.shootTimer > 0.4) {
                e.nextAttackTelegraph = 0.5;
            }
            if (e.nextAttackTelegraph > 0) {
                e.nextAttackTelegraph = Math.max(0, e.nextAttackTelegraph - dt);
            }
            
            // Patrones de ataque por fase
            if (e.shootTimer <= 0) {
                if (currentPhase === 1) {
                    // Fase 1: Círculos + ráfagas básicas
                    if (Math.random() < 0.5) {
                        shootCircle(e, 16, 160);
                    } else {
                        shootBurst(e, player, 5);
                    }
                    e.shootTimer = 1.2;
                } else if (currentPhase === 2) {
                    // Fase 2: Espirales + proyectiles guiados
                    if (Math.random() < 0.6) {
                        shootSpiral(e, 10, 170);
                    } else {
                        shootAimed(e, player, 6, 0.3, 140, {tracking: 0.04});
                    }
                    e.shootTimer = 0.8;
                } else if (currentPhase === 3) {
                    // Fase 3: Barrages rápidas + laser sweep
                    if (Math.random() < 0.7) {
                        shootCircle(e, 24, 200);
                    } else {
                        shootCone(e, player, 8, 1.5, 220);
                    }
                    e.shootTimer = 0.6;
                } else {
                    // Fase 4 ENRAGE: TODO MEZCLADO
                    const r = Math.random();
                    if (r < 0.3) {
                        shootCircle(e, 30, 180);
                    } else if (r < 0.6) {
                        shootAimed(e, player, 12, 0.5, 240);
                    } else {
                        shootSpiral(e, 12, 200);
                        shootBurst(e, player, 8);
                    }
                    e.shootTimer = 0.4;
                }
            }
        }

        // Disparo para enemigos normales
        if (!e.type.is_boss) {
            e.shootTimer -= dt;
            if (e.shootTimer <= 0) {
                const attackList = e.type.attacks && e.type.attacks.length ? e.type.attacks : ['circle'];
                // Ciclar por los ataques específicos para garantizar que se usen todos
                if (typeof e.attackIndex !== 'number') e.attackIndex = Math.floor(Math.random() * attackList.length);
                const pattern = attackList[e.attackIndex % attackList.length];
                e.attackIndex = (e.attackIndex + 1) % attackList.length;
                const meta = ATTACKS[pattern] || { name: pattern };
                // Guardar para debug/overlay
                e._lastPattern = pattern;

                if (typeof handleEnemyPattern === 'function') {
                    handleEnemyPattern(e, pattern, meta);
                } else {
                    shootCircle(e, meta.count || 8, meta.speed || 140);
                    e.shootTimer = e.type.shootInterval || 1.6 + Math.random() * 1.2;
                }
            }
        }
    }
    } // Fin del if (!timeStopped) para enemigos

    // Actualizar balas enemigas (congelar si time stop)
    if (!timeStopped) {
    for (let i = enemyBullets.length - 1; i >= 0; i--) {
        const b = enemyBullets[i];
        b.x += b.vx * dt;
        b.y += b.vy * dt;

        if (b.x < -30 || b.x > CONFIG.CANVAS_W + 30 || 
            b.y < -30 || b.y > CONFIG.CANVAS_H + 30) {
            enemyBullets.splice(i, 1);
            continue;
        }

        if (circleRect(b.x, b.y, b.r, player)) {
            if (b.effect === 'poison') {
                player.effects.poisonTimer = Math.max(player.effects.poisonTimer, b.dotDuration || 3);
                player.effects.poisonTickInterval = b.tick || 0.5;
                player.effects.poisonTick = player.effects.poisonTickInterval;
            }
            if (b.effect === 'slow') {
                player.effects.slowTimer = Math.max(player.effects.slowTimer, b.duration || 1.5);
                player.effects.slowAmount = b.slowAmount || 0.4;
            }

            // No aplicar daño si invulnerable (dash/shield)
            if (!player.effects.invulnerable && !player.effects.shieldTimer) {
                const damageAmount = 1 + (player.passiveDamage || 0);
                player.health = Math.max(0, player.health - damageAmount);
            }
            enemyBullets.splice(i, 1);

            if (player.health <= 0) {
                showDeathScreen();
                return;
            }
        }
    }
    } // Fin del if (!timeStopped) para balas

    // Limpieza y muertes por habilidades/otros daños (enemigos con hp <= 0)
    for (let j = enemies.length - 1; j >= 0; j--) {
        const e = enemies[j];
        if (e.hp <= 0) {
            if (typeof createExplosion === 'function') {
                const color = e.type && e.type.color ? e.type.color : '#fff';
                const size = e.type && e.type.size ? e.type.size : 20;
                createExplosion(e.x, e.y, color, size);
            }
            // Efectos visuales al matar
            if (e.type && e.type.is_boss) {
                if (typeof shakeScreen === 'function') shakeScreen(20, 0.5);
                if (typeof triggerSlowMo === 'function') triggerSlowMo(1.2, 0.3);
            } else {
                if (typeof shakeScreen === 'function') shakeScreen(4, 0.15);
                if (typeof triggerSlowMo === 'function') triggerSlowMo(0.15, 0.6);
            }
            enemies.splice(j, 1);
            totalKills++;
            if (typeof checkSkillMilestone === 'function') {
                checkSkillMilestone();
            }
        }
    }

    // Verificar habitación limpia
    if (enemies.length === 0 && !roomCleared) {
        roomCleared = true;
    }

    // Revisar cofres: abrir si el jugador se acerca
    for (let i = hazards.length - 1; i >= 0; i--) {
        const h = hazards[i];
        if (h.type === 'chest' && !h.opened) {
            // distancia simple
            const dx = (player.x + player.w/2) - h.x;
            const dy = (player.y + player.h/2) - h.y;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 40) {
                // Abrir cofre
                h.opened = true;
                // 10% de chance a mimic extra en abrir
                if (Math.random() < 0.10) {
                    const mimicType = ENEMY_TYPES.MIMIC || { color: '#d35400', size: 24, hp: 4 };
                    enemies.push({ x: h.x, y: h.y, type: mimicType, hp: mimicType.hp, maxHp: mimicType.hp, shootTimer: 1.0, moveTimer: 1.0, moveSpeed: 30 });
                } else {
                    // Otorgar habilidad al jugador (elegir skill al azar)
                    if (typeof SKILLS !== 'undefined' && Object.keys(SKILLS).length > 0) {
                        const keys = Object.keys(SKILLS);
                        const pick = keys[Math.floor(Math.random() * keys.length)];
                        if (typeof chooseSkill === 'function') {
                            // Si overlay no está activo, otorgar automáticamente
                            if (!skillOverlayActive) {
                                chooseSkill(pick);
                            } else {
                                // si overlay está activo, simplemente push a pending choices
                                pendingSkillChoices.push(pick);
                                renderSkillOverlay();
                            }
                        } else if (typeof acquiredSkills !== 'undefined') {
                            if (acquiredSkills.indexOf(pick) === -1) acquiredSkills.push(pick);
                        }
                    }
                }
            }
        }
    }

    // Avanzar a siguiente habitación
    if (roomCleared && player.y < 60 && 
        player.x > CONFIG.CANVAS_W/2 - 50 && 
        player.x < CONFIG.CANVAS_W/2 + 50) {
        if (currentRoom >= CONFIG.MAX_ROOMS) {
            // Llegaste al tope: victoria de mazmorra
            showVictoryScreen();
        } else {
            loadRoom(currentRoom + 1);
        }
    }

    updateUI();
}
