// skills_client.js - manejador de selección y activación de skills
// Expects globals: SKILLS, player, totalKills, gameStarted

var acquiredSkills = [];
var activeSkills = {}; // {Q: skillKey, E: skillKey, R: skillKey, F: skillKey, C: skillKey}
var skillCooldowns = {}; // {skillKey: remainingTime}
var skillDurations = {}; // {skillKey: remainingTime}
var skillOverlayActive = false;
var pendingSkillChoices = [];

function checkSkillMilestone() {
    if (!gameStarted) return;
    if (totalKills > 0 && totalKills % 10 === 0) {
        // Evitar lanzar varias veces en el mismo totalKills
        if (window._lastSkillMilestone === totalKills) return;
        window._lastSkillMilestone = totalKills;
        openSkillSelection();
    }
}

function openSkillSelection() {
    if (!SKILLS) return;
    skillOverlayActive = true;
    // Elegir 3 skills aleatorias que aún no tengas (o repetir si <3 disponibles)
    const keys = Object.keys(SKILLS).filter(k => acquiredSkills.indexOf(k) === -1);
    const pool = keys.length ? keys : Object.keys(SKILLS);
    const picks = [];
    for (let i=0; i<3; i++) {
        const pick = pool[Math.floor(Math.random()*pool.length)];
        picks.push(pick);
    }
    pendingSkillChoices = picks;
    renderSkillOverlay();
}

function renderSkillOverlay() {
    let el = document.getElementById('skillOverlay');
    if (!el) {
        el = document.createElement('div');
        el.id = 'skillOverlay';
        el.style.position='fixed';
        el.style.top='0';
        el.style.left='0';
        el.style.width='100%';
        el.style.height='100%';
        el.style.background='rgba(10,10,20,0.85)';
        el.style.display='flex';
        el.style.flexDirection='column';
        el.style.alignItems='center';
        el.style.justifyContent='center';
        el.style.zIndex='2000';
        el.style.fontFamily='Arial';
        document.body.appendChild(el);
    }
    el.innerHTML = '<h2 style="margin-bottom:24px;color:#f39c12;">¡Elige una habilidad!</h2>' +
        '<div style="display:flex;gap:20px;">' + pendingSkillChoices.map(k => {
            const data = SKILLS[k];
            return '<button style="padding:16px 20px;min-width:180px;background:#222;border:2px solid #555;color:#fff;border-radius:10px;cursor:pointer;font-size:14px;text-align:left;" onclick="chooseSkill(\''+k+'\')">'+
                '<strong>'+k+'</strong><br><span style="font-size:12px;opacity:0.8;">'+data.description+'</span>'+
                '</button>';
        }).join('') + '</div>' +
        '<p style="margin-top:30px;font-size:12px;color:#ccc;">Cada 10 kills obtienes una nueva habilidad.</p>';
}

function chooseSkill(key) {
    if (acquiredSkills.indexOf(key) === -1) {
        acquiredSkills.push(key);
        const skill = SKILLS[key];
        
        // Asignar a slot según tipo
        if (skill.type === 'active' && skill.key) {
            activeSkills[skill.key] = key;
            skillCooldowns[key] = 0;
        }
        
        // Aplicar pasivas inmediatamente
        if (skill.type === 'passive') {
            applyPassiveSkill(key, skill);
        }
    }
    
    skillOverlayActive = false;
    const ov = document.getElementById('skillOverlay');
    if (ov) ov.remove();
}

function applyPassiveSkill(key, skill) {
    if (skill.effect === 'health_boost') {
        CONFIG.MAX_HEALTH += skill.amount;
        player.health = Math.min(player.health + skill.amount, CONFIG.MAX_HEALTH);
    } else if (skill.effect === 'speed_boost') {
        if (!player.passiveSpeedMult) player.passiveSpeedMult = 1;
        player.passiveSpeedMult *= skill.multiplier;
    } else if (skill.effect === 'damage_boost') {
        if (!player.passiveDamage) player.passiveDamage = 0;
        player.passiveDamage += skill.amount;
    } else if (skill.effect === 'fire_rate_boost') {
        if (!player.fireRateMult) player.fireRateMult = 1;
        player.fireRateMult *= skill.multiplier;
    }
}

function activateSkill(skillKey) {
    const skill = SKILLS[skillKey];
    if (!skill || skill.type !== 'active') return;
    if (skillCooldowns[skillKey] && skillCooldowns[skillKey] > 0) return;
    
    // Aplicar cooldown
    skillCooldowns[skillKey] = skill.cooldown;
    
    // Efectos inmediatos
    switch (skill.effect) {
        case 'heal':
            player.health = Math.min(player.health + skill.amount, CONFIG.MAX_HEALTH);
            createHealEffect(player.x + player.w/2, player.y + player.h/2);
            break;
        case 'super_heal':
            player.health = Math.min(player.health + skill.amount, CONFIG.MAX_HEALTH);
            if (skill.regen) {
                player.effects.regen = skill.regen;
                player.effects.regenDuration = skill.regenDuration;
            }
            createHealEffect(player.x + player.w/2, player.y + player.h/2);
            break;
        case 'life_drain':
            let drained = 0;
            for (let e of enemies) {
                const dist = Math.sqrt(Math.pow(e.x - player.x, 2) + Math.pow(e.y - player.y, 2));
                if (dist < skill.radius) {
                    e.hp -= skill.drainAmount;
                    drained += skill.drainAmount;
                    // Línea visual de drenaje
                    if (typeof createDrainLine === 'function') {
                        createDrainLine(e.x, e.y, player.x + player.w/2, player.y + player.h/2);
                    }
                }
            }
            player.health = Math.min(player.health + drained, CONFIG.MAX_HEALTH);
            break;
        case 'dash':
            player.effects.dashTimer = skill.duration;
            player.effects.dashSpeed = skill.speedMultiplier;
            player.effects.invulnerable = skill.invulnerable;
            break;
        case 'teleport':
            // Teletransporte hacia la dirección de movimiento
            let tx = player.x, ty = player.y;
            if (keys.right) tx += skill.distance;
            if (keys.left) tx -= skill.distance;
            if (keys.down) ty += skill.distance;
            if (keys.up) ty -= skill.distance;
            tx = Math.max(CONFIG.BORDER, Math.min(tx, CONFIG.CANVAS_W - player.w - CONFIG.BORDER));
            ty = Math.max(CONFIG.BORDER, Math.min(ty, CONFIG.CANVAS_H - player.h - CONFIG.BORDER));
            if (typeof createTeleportEffect === 'function') {
                createTeleportEffect(player.x, player.y);
                createTeleportEffect(tx, ty);
            }
            player.x = tx;
            player.y = ty;
            break;
        case 'rage':
        case 'berserk':
            skillDurations[skillKey] = skill.duration;
            player.effects.damageMultiplier = skill.damageMultiplier;
            player.effects.rageSpeedMult = skill.speedMultiplier || 1;
            if (skill.healthCost) {
                player.health = Math.max(1, player.health * (1 - skill.healthCost));
            }
            break;
        case 'shield':
            skillDurations[skillKey] = skill.duration;
            player.effects.shieldTimer = skill.duration;
            break;
        case 'invisibility':
            skillDurations[skillKey] = skill.duration;
            player.effects.invisible = skill.duration;
            player.effects.invisSpeedMult = skill.speedMultiplier || 1;
            break;
        case 'time_stop':
            skillDurations[skillKey] = skill.duration;
            if (typeof window !== 'undefined') window.timeStopTimer = skill.duration;
            break;
        case 'energy_burst':
            // Dañar enemigos cercanos
            for (let e of enemies) {
                const dist = Math.sqrt(Math.pow(e.x - player.x, 2) + Math.pow(e.y - player.y, 2));
                if (dist < skill.radius) {
                    e.hp -= skill.damage;
                    // Empujar
                    const angle = Math.atan2(e.y - player.y, e.x - player.x);
                    e.vx = Math.cos(angle) * skill.knockback;
                    e.vy = Math.sin(angle) * skill.knockback;
                }
            }
            if (typeof createBurstEffect === 'function') {
                createBurstEffect(player.x + player.w/2, player.y + player.h/2, skill.radius);
            }
            break;
        case 'gravity_well':
            skillDurations[skillKey] = skill.duration;
            hazards.push({
                type: 'gravity_well',
                x: player.x + player.w/2,
                y: player.y + player.h/2,
                r: skill.radius,
                pullStrength: skill.pullStrength,
                slowAmount: skill.slowAmount,
                duration: skill.duration,
                color: skill.color
            });
            break;
    }
}

function updateSkills(dt) {
    // Actualizar cooldowns
    for (let key in skillCooldowns) {
        if (skillCooldowns[key] > 0) {
            skillCooldowns[key] = Math.max(0, skillCooldowns[key] - dt);
        }
    }
    
    // Actualizar duraciones
    for (let key in skillDurations) {
        if (skillDurations[key] > 0) {
            skillDurations[key] = Math.max(0, skillDurations[key] - dt);
            if (skillDurations[key] === 0) {
                const skill = SKILLS[key];
                // Limpiar efectos al terminar
                if (skill.effect === 'rage' || skill.effect === 'berserk') {
                    player.effects.damageMultiplier = 1;
                    player.effects.rageSpeedMult = 1;
                } else if (skill.effect === 'invisibility') {
                    player.effects.invisible = 0;
                    player.effects.invisSpeedMult = 1;
                }
            }
        }
    }
    
    // Efectos continuos
    if (player.effects.dashTimer > 0) {
        player.effects.dashTimer -= dt;
        if (player.effects.dashTimer <= 0) {
            player.effects.invulnerable = false;
        }
    }
    
    if (player.effects.regen && player.effects.regenDuration > 0) {
        player.effects.regenDuration -= dt;
        if (!player.effects.regenTick) player.effects.regenTick = 0;
        player.effects.regenTick -= dt;
        if (player.effects.regenTick <= 0) {
            player.health = Math.min(player.health + player.effects.regen, CONFIG.MAX_HEALTH);
            player.effects.regenTick = 1;
        }
        if (player.effects.regenDuration <= 0) {
            player.effects.regen = 0;
        }
    }
    
    // Time stop
    if (typeof window !== 'undefined' && window.timeStopTimer > 0) {
        window.timeStopTimer = Math.max(0, window.timeStopTimer - dt);
    }
    
    // Actualizar HUD de skills
    updateSkillHUD();
}

function updateSkillHUD() {
    const skillBar = document.getElementById('skillBar');
    if (!skillBar) return;
    
    // Mostrar barra si hay skills activas
    const hasSkills = Object.keys(activeSkills).some(k => activeSkills[k]);
    skillBar.style.display = hasSkills ? 'flex' : 'none';
    
    // Actualizar cada slot
    ['Q', 'E', 'R', 'F', 'C'].forEach(key => {
        const slot = skillBar.querySelector(`[data-key="${key}"]`);
        if (!slot) return;
        
        const skillKey = activeSkills[key];
        if (!skillKey) {
            slot.classList.add('empty');
            slot.classList.remove('ready', 'active');
            const iconEl = slot.querySelector('.skill-icon');
            if (iconEl) iconEl.remove();
            return;
        }
        
        slot.classList.remove('empty');
        const skill = SKILLS[skillKey];
        if (!skill) return;
        
        // Añadir icono si no existe
        let iconEl = slot.querySelector('.skill-icon');
        if (!iconEl) {
            iconEl = document.createElement('div');
            iconEl.className = 'skill-icon';
            slot.appendChild(iconEl);
        }
        iconEl.textContent = getSkillIcon(skill.effect);
        iconEl.style.color = skill.color || '#ecf0f1';
        
        // Cooldown overlay
        const cooldownEl = slot.querySelector('.skill-cooldown');
        const cooldown = skillCooldowns[skillKey] || 0;
        const maxCooldown = skill.cooldown || 1;
        const cooldownPercent = Math.min(100, (cooldown / maxCooldown) * 100);
        cooldownEl.style.height = cooldownPercent + '%';
        
        // Duration bar
        let durationEl = slot.querySelector('.skill-duration');
        const duration = skillDurations[skillKey] || 0;
        if (duration > 0 && skill.duration) {
            if (!durationEl) {
                durationEl = document.createElement('div');
                durationEl.className = 'skill-duration';
                slot.appendChild(durationEl);
            }
            const durationPercent = Math.max(0, (duration / skill.duration) * 100);
            durationEl.style.width = durationPercent + '%';
            slot.classList.add('active');
        } else {
            if (durationEl) durationEl.remove();
            slot.classList.remove('active');
        }
        
        // Ready state
        if (cooldown === 0) {
            slot.classList.add('ready');
        } else {
            slot.classList.remove('ready');
        }
    });
}

function getSkillIcon(effect) {
    const icons = {
        heal: '❤',
        super_heal: '💚',
        life_drain: '🩸',
        dash: '⚡',
        teleport: '🌀',
        rage: '🔥',
        berserk: '💥',
        energy_burst: '💫',
        shield: '🛡',
        invisibility: '👻',
        time_stop: '⏸',
        gravity_well: '🌑'
    };
    return icons[effect] || '✦';
}

// Hook sencillo: se puede llamar desde update()
window.checkSkillMilestone = checkSkillMilestone;
window.activateSkill = activateSkill;
window.updateSkills = updateSkills;