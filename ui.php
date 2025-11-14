<?php
// ui.php - HUD markup y lógica UI (se incluye después del main script)
?>

<div class="stats" aria-live="polite">
    <div class="stat-row">
        <div class="label">Vida</div>
        <div class="health-wrap">
            <div class="health-bar" aria-hidden="true"><div id="healthFill" class="health-fill"></div></div>
            <div id="healthText" class="health-text"><?php echo $config['player_health']; ?></div>
        </div>
    </div>
    <div class="stat-row">
        <div class="label">Habitación</div>
        <div id="room" class="value">1</div>
    </div>
    <div class="stat-row">
        <div class="label">Kills</div>
        <div id="kills" class="value">0</div>
    </div>
</div>

<div id="skillBar" class="skill-bar" style="display:none;">
    <div class="skill-slot" data-key="Q"><span class="skill-key">Q</span><div class="skill-cooldown"></div></div>
    <div class="skill-slot" data-key="E"><span class="skill-key">E</span><div class="skill-cooldown"></div></div>
    <div class="skill-slot" data-key="R"><span class="skill-key">R</span><div class="skill-cooldown"></div></div>
    <div class="skill-slot" data-key="F"><span class="skill-key">F</span><div class="skill-cooldown"></div></div>
    <div class="skill-slot" data-key="C"><span class="skill-key">C</span><div class="skill-cooldown"></div></div>
</div>

<script>
// updateUI ahora está definido aquí y se ejecutará después de que el main script haya definido
// las variables globales (player, CONFIG, totalKills, etc.).
window.updateUI = function() {
    const statsEl = document.querySelector('.stats');
    const menuActive = document.getElementById('menuScreen').classList.contains('active');
    const deathActive = document.getElementById('deathScreen').classList.contains('active');
    if (statsEl) {
        statsEl.style.display = (!menuActive && !deathActive && typeof gameStarted !== 'undefined' && gameStarted) ? 'block' : 'none';
    }

    const hf = document.getElementById('healthFill');
    const ht = document.getElementById('healthText');
    if (hf && typeof player !== 'undefined') {
        const maxBar = Math.max(CONFIG.MAX_HEALTH, 100);
        const pct = Math.max(0, Math.min(1, player.health / maxBar));
        
        // Animación suave de la barra
        const currentWidth = parseFloat(hf.style.width) || 100;
        const targetWidth = pct * 100;
        const newWidth = currentWidth + (targetWidth - currentWidth) * 0.15;
        hf.style.width = newWidth + '%';
        
        // Cambio de color según vida
        if (pct > 0.6) {
            hf.style.background = 'linear-gradient(90deg, #4CAF50, #66BB6A)';
        } else if (pct > 0.3) {
            hf.style.background = 'linear-gradient(90deg, #FFC107, #FFB300)';
        } else {
            hf.style.background = 'linear-gradient(90deg, #e74c3c, #c0392b)';
            hf.style.animation = 'healthPulse 0.5s infinite';
        }
        
        // Flash en daño
        if (!window._lastHealth) window._lastHealth = player.health;
        if (player.health < window._lastHealth) {
            hf.style.boxShadow = '0 0 20px rgba(255, 0, 0, 0.8)';
            setTimeout(() => { hf.style.boxShadow = 'none'; }, 200);
        }
        window._lastHealth = player.health;
    }
    if (ht && typeof player !== 'undefined') ht.textContent = Math.ceil(player.health);

    const r = document.getElementById('room');
    if (r && typeof currentRoom !== 'undefined') {
        // Mostrar progreso hacia boss final
        const nextBoss = Math.ceil(currentRoom / 10) * 10;
        const finalBoss = 200;
        r.textContent = currentRoom + (nextBoss <= finalBoss ? ` (Boss: ${nextBoss})` : ' [FINAL REACHED]');
    }
    const k = document.getElementById('kills');
    if (k) k.textContent = (typeof totalKills !== 'undefined' ? totalKills : 0);
};
</script>

<!-- Pantallas (menú / muerte) movidas a ui.php para centralizar la UI -->
<div id="menuScreen" class="screen active">
    <h1>🎮 BULLET HELL 🎮</h1>
    <p>WASD - Mover tu personaje</p>
    <p>Flechas - Disparar</p>
    <p>Elimina todos los enemigos para abrir la puerta</p>
    <p style="margin-top: 20px; font-size: 14px; opacity: 0.8; color: #f39c12;">⚔️ 20 BOSSES ÉPICOS ⚔️</p>
    <p style="font-size: 13px; opacity: 0.7;">Boss cada 10 habitaciones | Boss Final: Room 200</p>
    <p style="font-size: 12px; opacity: 0.6; margin-top: 10px;">
        Etapa I (10-50): Territorial<br>
        Etapa II (60-100): Bestia<br>
        Etapa III (110-150): Planetaria<br>
        Etapa IV (160-200): Cósmica
    </p>
    <button onclick="startGame()">INICIAR JUEGO</button>
</div>

<!-- Pantalla de muerte -->
<div id="deathScreen" class="screen">
    <h2>💀 GAME OVER 💀</h2>
    <div class="stats-final">
        <p>Habitación alcanzada: <strong id="finalRoom">0</strong></p>
        <p>Enemigos eliminados: <strong id="finalKills">0</strong></p>
    <div style="margin-top:20px; display:flex; justify-content:center; gap:20px;">
        <button onclick="restartGame()">REINTENTAR</button>
        <button onclick="backToMenu()" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);">MENÚ</button>
    </div></div>
</div>

<!-- Pantalla de VICTORIA -->
<div id="victoryScreen" class="screen" style="display:none;">
    <h1 style="color: #f1c40f; text-shadow: 0 0 20px rgba(241, 196, 15, 0.8);">👑 ¡VICTORIA TOTAL! 👑</h1>
    <h2 style="color: #ecf0f1; margin-top: 10px;">Has derrotado a THE ABSOLUTE DEITY</h2>
    <div class="stats-final" style="margin-top: 30px;">
        <p style="font-size: 20px; color: #2ecc71;">🏆 JUEGO COMPLETADO 🏆</p>
        <p style="margin-top: 15px;">Habitación final: <strong style="color: #f39c12;">200</strong></p>
        <p>Enemigos eliminados: <strong id="victoryKills" style="color: #e74c3c;">0</strong></p>
        <p style="margin-top: 20px; font-size: 14px; opacity: 0.8;">
            Has conquistado las 4 etapas:<br>
            <span style="color: #4CAF50;">✓ Territorial</span> | 
            <span style="color: #FF9800;">✓ Bestia</span> | 
            <span style="color: #9C27B0;">✓ Planetaria</span> | 
            <span style="color: #F44336;">✓ Cósmica</span>
        </p>
    </div>
    <div style="margin-top:30px; display:flex; justify-content:center; gap:20px;">
        <button onclick="restartFromVictory()" style="background: linear-gradient(135deg, #f39c12, #e67e22);">JUGAR DE NUEVO</button>
        <button onclick="backFromVictory()">MENÚ</button>
    </div>
</div>

<script>
// === EFECTOS VISUALES AVANZADOS 200% ===
// Screen shake
window.screenShake = { x: 0, y: 0, intensity: 0, duration: 0 };

// Chromatic Aberration
window.chromaticEffect = { active: false, intensity: 0 };
function triggerChromatic(intensity, duration) {
    window.chromaticEffect.active = true;
    window.chromaticEffect.intensity = intensity;
    window.chromaticEffect.duration = duration;
}

// Bloom effect
window.bloomEffect = { active: true, intensity: 0.3 };

// Distorsión temporal
window.timeDistortion = { active: false, amount: 0 };
function triggerTimeDistortion(amount, duration) {
    window.timeDistortion.active = true;
    window.timeDistortion.amount = amount;
    window.timeDistortion.duration = duration;
}

// Background parallax layers
window.bgLayers = [];
function initBackgroundLayers() {
    if (window.bgLayers.length > 0) return;
    // 3 capas de parallax
    for (let layer = 0; layer < 3; layer++) {
        const stars = [];
        const count = 30 - (layer * 8);
        for (let i = 0; i < count; i++) {
            stars.push({
                x: Math.random() * CONFIG.CANVAS_W,
                y: Math.random() * CONFIG.CANVAS_H,
                size: (1 + layer) * (0.8 + Math.random() * 0.4),
                speed: 0.05 + layer * 0.15,
                alpha: 0.3 + layer * 0.2,
                parallaxSpeed: 1 + layer * 0.5
            });
        }
        window.bgLayers.push(stars);
    }
}

// Particle pool optimization
window.particlePool = [];
window.maxParticles = 500;

// Boss-specific effects
window.bossEffects = {
    aura: { rings: [], particles: [] },
    telegraph: { active: false, x: 0, y: 0, radius: 0, alpha: 0 },
    phaseTransition: { active: false, progress: 0 }
};
function shakeScreen(intensity, duration) {
    window.screenShake.intensity = intensity;
    window.screenShake.duration = duration;
}
function updateScreenShake(dt) {
    if (window.screenShake.duration > 0) {
        window.screenShake.duration -= dt;
        const shake = window.screenShake.intensity * (window.screenShake.duration / 0.3);
        window.screenShake.x = (Math.random() - 0.5) * shake * 2;
        window.screenShake.y = (Math.random() - 0.5) * shake * 2;
    } else {
        window.screenShake.x = 0;
        window.screenShake.y = 0;
    }
}

// Slow motion effect
window.slowMotion = { active: false, duration: 0, timeScale: 1 };
function triggerSlowMo(duration, scale) {
    window.slowMotion.active = true;
    window.slowMotion.duration = duration;
    window.slowMotion.timeScale = scale;
}
function updateSlowMo(dt) {
    if (window.slowMotion.active && window.slowMotion.duration > 0) {
        window.slowMotion.duration -= dt;
    } else {
        window.slowMotion.active = false;
        window.slowMotion.timeScale = 1;
    }
}

// Update chromatic aberration
function updateChromaticEffect(dt) {
    if (window.chromaticEffect && window.chromaticEffect.active) {
        if (window.chromaticEffect.duration > 0) {
            window.chromaticEffect.duration -= dt;
            window.chromaticEffect.intensity *= 0.95; // Fade out
        } else {
            window.chromaticEffect.active = false;
            window.chromaticEffect.intensity = 0;
        }
    }
}

// Player trail
window.playerTrail = [];
function addPlayerTrail() {
    if (!player) return;
    window.playerTrail.push({
        x: player.x + player.w/2,
        y: player.y + player.h/2,
        life: 0.3,
        alpha: 0.6
    });
    if (window.playerTrail.length > 15) window.playerTrail.shift();
}
function updatePlayerTrail(dt) {
    for (let i = window.playerTrail.length - 1; i >= 0; i--) {
        const t = window.playerTrail[i];
        t.life -= dt;
        t.alpha = Math.max(0, t.life / 0.3 * 0.6);
        if (t.life <= 0) window.playerTrail.splice(i, 1);
    }
}
function drawPlayerTrail() {
    for (let t of window.playerTrail) {
        ctx.fillStyle = `rgba(41, 181, 246, ${t.alpha})`;
        ctx.beginPath();
        ctx.arc(t.x, t.y, 12, 0, Math.PI * 2);
        ctx.fill();
    }
}

// Sistema de partículas global
if (!window.particles) window.particles = [];

function createExplosion(x, y, color, size) {
    const count = Math.floor(size / 3) + 8;
    for (let i = 0; i < count; i++) {
        const angle = (Math.PI * 2 * i) / count + Math.random() * 0.3;
        const speed = 80 + Math.random() * 120;
        window.particles.push({
            x: x,
            y: y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            life: 1,
            maxLife: 1,
            size: 2 + Math.random() * 3,
            color: color,
            gravity: 100
        });
    }
}

function updateParticles(dt) {
    for (let i = window.particles.length - 1; i >= 0; i--) {
        const p = window.particles[i];
        p.x += p.vx * dt;
        p.y += p.vy * dt;
        p.vy += (p.gravity || 0) * dt;
        p.vx *= 0.97;
        p.life -= dt * 2;
        if (p.life <= 0) {
            window.particles.splice(i, 1);
        }
    }
}

function drawParticles() {
    for (let p of window.particles) {
        const alpha = p.life / p.maxLife;
        ctx.save();
        ctx.globalAlpha = alpha;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }
}

// Efectos visuales para skills
function createHealEffect(x, y) {
    for (let i = 0; i < 20; i++) {
        const angle = (Math.PI * 2 * i) / 20;
        window.particles.push({
            x: x, y: y,
            vx: Math.cos(angle) * 60,
            vy: Math.sin(angle) * 60 - 100,
            life: 1, maxLife: 1,
            size: 3,
            color: '#4CAF50',
            gravity: -50
        });
    }
}

function createTeleportEffect(x, y) {
    for (let i = 0; i < 30; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = 50 + Math.random() * 100;
        window.particles.push({
            x: x, y: y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            life: 0.8, maxLife: 0.8,
            size: 2 + Math.random() * 2,
            color: '#9C27B0',
            gravity: 0
        });
    }
}

function createBurstEffect(x, y, radius) {
    for (let i = 0; i < 40; i++) {
        const angle = (Math.PI * 2 * i) / 40;
        const speed = 200 + Math.random() * 100;
        window.particles.push({
            x: x, y: y,
            vx: Math.cos(angle) * speed,
            vy: Math.sin(angle) * speed,
            life: 1, maxLife: 1,
            size: 4,
            color: '#FFC107',
            gravity: 0
        });
    }
}

function createDrainLine(fromX, fromY, toX, toY) {
    // Línea de partículas desde enemigo a jugador
    const steps = 10;
    for (let i = 0; i < steps; i++) {
        const t = i / steps;
        window.particles.push({
            x: fromX + (toX - fromX) * t,
            y: fromY + (toY - fromY) * t,
            vx: (toX - fromX) * 2,
            vy: (toY - fromY) * 2,
            life: 0.5, maxLife: 0.5,
            size: 3,
            color: '#E91E63',
            gravity: 0
        });
    }
}

window.createHealEffect = createHealEffect;
window.createTeleportEffect = createTeleportEffect;
window.createBurstEffect = createBurstEffect;
window.createDrainLine = createDrainLine;

// draw() moved here to keep all UI and menus together in the include.
function draw() {
    // Init background layers
    if (typeof initBackgroundLayers === 'function' && window.bgLayers.length === 0) {
        initBackgroundLayers();
    }
    
    // Apply screen shake
    ctx.save();
    ctx.translate(window.screenShake.x, window.screenShake.y);
    
    // Apply chromatic aberration if active
    if (window.chromaticEffect && window.chromaticEffect.active) {
        const offset = window.chromaticEffect.intensity * 3;
        ctx.save();
        ctx.globalCompositeOperation = 'screen';
        ctx.fillStyle = `rgba(255, 0, 0, ${window.chromaticEffect.intensity * 0.3})`;
        ctx.translate(offset, 0);
        ctx.fillRect(-50, -50, CONFIG.CANVAS_W + 100, CONFIG.CANVAS_H + 100);
        ctx.translate(-offset * 2, 0);
        ctx.fillStyle = `rgba(0, 255, 255, ${window.chromaticEffect.intensity * 0.3})`;
        ctx.fillRect(-50, -50, CONFIG.CANVAS_W + 100, CONFIG.CANVAS_H + 100);
        ctx.restore();
    }
    
    // keep identical rendering behavior as before
    ctx.clearRect(-50, -50, CONFIG.CANVAS_W + 100, CONFIG.CANVAS_H + 100);

    // Fondo con gradiente dinámico según tier del boss
    const time = performance.now() / 1000;
    let bgColor1 = '#0d0a18', bgColor2 = '#1a1825', bgColor3 = '#2a2438';
    
    // Detectar si hay boss y ajustar colores según tier
    const hasBoss = enemies && enemies.find(e => e.type && (e.type.is_boss || e.bossNumber));
    if (hasBoss) {
        const tier = hasBoss.tier || 1;
        if (tier === 1) {
            bgColor1 = '#1a0f0f'; bgColor2 = '#2a1520'; bgColor3 = '#3a2030';
        } else if (tier === 2) {
            bgColor1 = '#0f1a1a'; bgColor2 = '#152a30'; bgColor3 = '#203a40';
        } else if (tier === 3) {
            bgColor1 = '#1a0f1f'; bgColor2 = '#2a1530'; bgColor3 = '#3a2040';
        } else if (tier === 4) {
            bgColor1 = '#050508'; bgColor2 = '#0a0a10'; bgColor3 = '#0f0f18';
        }
    }
    
    const gradient = ctx.createLinearGradient(0, 0, 0, CONFIG.CANVAS_H);
    gradient.addColorStop(0, bgColor1);
    gradient.addColorStop(0.5, bgColor2);
    gradient.addColorStop(1, bgColor3);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, CONFIG.CANVAS_W, CONFIG.CANVAS_H);
    
    // Sistema de parallax mejorado con 3 capas
    if (window.bgLayers && window.bgLayers.length > 0) {
        for (let layerIdx = 0; layerIdx < window.bgLayers.length; layerIdx++) {
            const layer = window.bgLayers[layerIdx];
            for (let star of layer) {
                const pulse = Math.sin(time * star.speed + star.x) * 0.3;
                const alpha = Math.max(0, Math.min(1, star.alpha + pulse));
                
                // Parallax effect basado en movimiento del jugador
                let offsetX = 0, offsetY = 0;
                if (typeof player !== 'undefined' && player.vx !== undefined) {
                    offsetX = -(player.vx || 0) * star.parallaxSpeed * 0.02;
                    offsetY = -(player.vy || 0) * star.parallaxSpeed * 0.02;
                }
                
                // Color según tier
                let starColor = '255, 255, 255';
                if (hasBoss) {
                    if (hasBoss.tier === 2) starColor = '100, 200, 255';
                    else if (hasBoss.tier === 3) starColor = '200, 100, 255';
                    else if (hasBoss.tier === 4) starColor = '255, 50, 100';
                }
                
                ctx.fillStyle = `rgba(${starColor}, ${alpha})`;
                ctx.beginPath();
                ctx.arc(star.x + offsetX, star.y + offsetY, star.size, 0, Math.PI * 2);
                ctx.fill();
                
                // Bloom effect en estrellas grandes
                if (window.bloomEffect && window.bloomEffect.active && star.size > 1.5) {
                    ctx.shadowBlur = 8 * window.bloomEffect.intensity;
                    ctx.shadowColor = `rgba(${starColor}, ${alpha * 0.6})`;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }
            }
        }
    }

    // Paredes con gradiente y resplandor
    const wallGrad = ctx.createLinearGradient(0, 0, 0, CONFIG.BORDER);
    wallGrad.addColorStop(0, '#1a1520');
    wallGrad.addColorStop(1, '#0a0a10');
    ctx.fillStyle = wallGrad;
    ctx.fillRect(0, 0, CONFIG.CANVAS_W, CONFIG.BORDER);
    ctx.fillRect(0, CONFIG.CANVAS_H - CONFIG.BORDER, CONFIG.CANVAS_W, CONFIG.BORDER);
    ctx.fillRect(0, 0, CONFIG.BORDER, CONFIG.CANVAS_H);
    ctx.fillRect(CONFIG.CANVAS_W - CONFIG.BORDER, 0, CONFIG.BORDER, CONFIG.CANVAS_H);

    // Borde brillante pulsante en paredes
    const borderPulse = 0.3 + Math.sin(time * 2) * 0.15;
    ctx.strokeStyle = `rgba(243, 156, 18, ${borderPulse})`;
    ctx.lineWidth = 3;
    ctx.strokeRect(CONFIG.BORDER/2, CONFIG.BORDER/2, 
                   CONFIG.CANVAS_W - CONFIG.BORDER, CONFIG.CANVAS_H - CONFIG.BORDER);
    
    // Inner glow
    ctx.strokeStyle = `rgba(243, 156, 18, ${borderPulse * 0.3})`;
    ctx.lineWidth = 1;
    ctx.strokeRect(CONFIG.BORDER/2 + 4, CONFIG.BORDER/2 + 4, 
                   CONFIG.CANVAS_W - CONFIG.BORDER - 8, CONFIG.CANVAS_H - CONFIG.BORDER - 8);

    // Puerta con animación elegante
    const doorOpen = (typeof roomCleared !== 'undefined' && roomCleared);
    const doorPulse = doorOpen ? (0.8 + Math.sin(time * 4) * 0.2) : 1;
    const doorX = CONFIG.CANVAS_W/2 - 40;
    const doorW = 80;
    
    if (doorOpen) {
        // Puerta abierta con resplandor verde
        const doorGlow = ctx.createRadialGradient(doorX + doorW/2, CONFIG.BORDER/2, 0, doorX + doorW/2, CONFIG.BORDER/2, 60);
        doorGlow.addColorStop(0, `rgba(76, 175, 80, ${doorPulse * 0.6})`);
        doorGlow.addColorStop(0.5, `rgba(76, 175, 80, ${doorPulse * 0.3})`);
        doorGlow.addColorStop(1, 'rgba(76, 175, 80, 0)');
        ctx.fillStyle = doorGlow;
        ctx.fillRect(doorX - 40, 0, doorW + 80, CONFIG.BORDER + 20);
        
        ctx.fillStyle = '#4CAF50';
        ctx.fillRect(doorX, 0, doorW, CONFIG.BORDER);
        
        // Borde dorado pulsante
        ctx.strokeStyle = `rgba(255, 215, 0, ${doorPulse})`;
        ctx.lineWidth = 4;
        ctx.strokeRect(doorX, 0, doorW, CONFIG.BORDER);
        
        // Partículas flotantes
        for (let i = 0; i < 8; i++) {
            const px = doorX + doorW/2 + Math.sin(time * 2 + i) * 30;
            const py = CONFIG.BORDER + (time * 20 + i * 10) % 40;
            ctx.fillStyle = `rgba(255, 215, 0, ${0.5 - (py / 80)})`;
            ctx.beginPath();
            ctx.arc(px, py, 2, 0, Math.PI * 2);
            ctx.fill();
        }
    } else {
        // Puerta cerrada con efecto locked
        const lockGrad = ctx.createLinearGradient(doorX, 0, doorX, CONFIG.BORDER);
        lockGrad.addColorStop(0, '#8B0000');
        lockGrad.addColorStop(1, '#5a0000');
        ctx.fillStyle = lockGrad;
        ctx.fillRect(doorX, 0, doorW, CONFIG.BORDER);
        
        ctx.strokeStyle = 'rgba(139, 0, 0, 0.8)';
        ctx.lineWidth = 2;
        ctx.strokeRect(doorX, 0, doorW, CONFIG.BORDER);
    }

    // Enemigos
    // Dibujar hazards (debajo de los enemigos para que se vean como obstáculos)
    for (let h of (typeof hazards !== 'undefined' ? hazards : [])) {
        if (h.type === 'web') {
            // Telaraña - gris translúcido
            ctx.fillStyle = 'rgba(200,200,255,0.15)';
            ctx.beginPath();
            ctx.ellipse(h.x, h.y, h.r, h.r/2, 0, 0, Math.PI*2);
            ctx.fill();
            ctx.strokeStyle = 'rgba(255,255,255,0.3)';
            ctx.lineWidth = 1;
            ctx.stroke();
        } else if (h.type === 'spore') {
            // Nube de esporas - verde venenoso
            ctx.fillStyle = 'rgba(100,255,100,0.12)';
            ctx.beginPath();
            ctx.arc(h.x, h.y, h.r, 0, Math.PI*2);
            ctx.fill();
            ctx.strokeStyle = 'rgba(50,200,50,0.3)';
            ctx.lineWidth = 2;
            ctx.stroke();
        } else if (h.type === 'fire') {
            // Piscina de fuego - rojo/naranja
            const fireGradient = ctx.createRadialGradient(h.x, h.y, 0, h.x, h.y, h.r);
            fireGradient.addColorStop(0, 'rgba(255,100,0,0.4)');
            fireGradient.addColorStop(0.5, 'rgba(255,50,0,0.2)');
            fireGradient.addColorStop(1, 'rgba(200,0,0,0.05)');
            ctx.fillStyle = fireGradient;
            ctx.beginPath();
            ctx.arc(h.x, h.y, h.r, 0, Math.PI*2);
            ctx.fill();
            // Efecto de llamas animadas
            const flicker = Math.sin(performance.now() / 100) * 0.1 + 0.9;
            ctx.strokeStyle = `rgba(255,150,0,${0.5 * flicker})`;
            ctx.lineWidth = 2;
            ctx.stroke();
        } else if (h.type === 'coil') {
            // Trampa de serpiente - verde oscuro enrollado
            ctx.strokeStyle = 'rgba(50,150,50,0.5)';
            ctx.lineWidth = 4;
            ctx.beginPath();
            for (let i = 0; i < 3; i++) {
                const offset = (i - 1) * 8;
                ctx.arc(h.x, h.y + offset, h.r - Math.abs(offset), 0, Math.PI*2);
            }
            ctx.stroke();
            ctx.fillStyle = 'rgba(30,100,30,0.1)';
            ctx.beginPath();
            ctx.arc(h.x, h.y, h.r, 0, Math.PI*2);
            ctx.fill();
        } else if (h.type === 'healing_altar') {
            // Healing altar removido (no debería existir); ignorar dibujo
        } else if (h.type === 'trap') {
            // Trampa - pinchos rojos
            ctx.save();
            ctx.translate(h.x, h.y);
            ctx.strokeStyle = '#c0392b';
            ctx.fillStyle = 'rgba(192,57,43,0.25)';
            ctx.lineWidth = 2;
            const spikes = 6;
            const R = h.r || 20;
            const r = (h.r || 20) * 0.45;
            ctx.beginPath();
            for (let i = 0; i < spikes; i++) {
                const a1 = (i / spikes) * Math.PI * 2;
                const a2 = ((i + 0.5) / spikes) * Math.PI * 2;
                ctx.lineTo(Math.cos(a1) * R, Math.sin(a1) * R);
                ctx.lineTo(Math.cos(a2) * r, Math.sin(a2) * r);
            }
            ctx.closePath();
            ctx.fill();
            ctx.stroke();
            ctx.restore();
            // Telegrafía: mostrar el próximo destino si existe
            if (h.nextX != null && h.nextY != null && h.telegraph && h.telegraph > 0) {
                ctx.save();
                ctx.globalAlpha = Math.min(1, h.telegraph / 0.85);
                ctx.strokeStyle = 'rgba(255,255,255,0.6)';
                ctx.lineWidth = 2;
                ctx.setLineDash([6,4]);
                ctx.beginPath();
                ctx.moveTo(h.x, h.y);
                ctx.lineTo(h.nextX, h.nextY);
                ctx.stroke();
                ctx.setLineDash([]);
                ctx.fillStyle = 'rgba(255,255,255,0.15)';
                ctx.beginPath();
                ctx.arc(h.nextX, h.nextY, (h.r||20)*0.9, 0, Math.PI*2);
                ctx.fill();
                ctx.restore();
            }
        }
    }
    for (let e of (typeof enemies !== 'undefined' ? enemies : [])) {
        // Sombra suave
        ctx.save();
        ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
        ctx.shadowBlur = 8;
        ctx.shadowOffsetY = 6;
        ctx.fillStyle = 'rgba(0, 0, 0, 0.3)';
        ctx.beginPath();
        ctx.ellipse(e.x, e.y + e.type.size/2 + 8, e.type.size/2.2, e.type.size/3.5, 0, 0, Math.PI*2);
        ctx.fill();
        ctx.restore();

        // Enemigo con gradiente radial
        const enemyGrad = ctx.createRadialGradient(e.x - e.type.size/4, e.y - e.type.size/4, 0, e.x, e.y, e.type.size/2);
        enemyGrad.addColorStop(0, e.type.color);
        const darkerColor = e.type.color.replace(/rgb\((\d+),(\d+),(\d+)\)/, (m, r, g, b) => 
            `rgb(${Math.max(0, r * 0.6)},${Math.max(0, g * 0.6)},${Math.max(0, b * 0.6)})`
        );
        enemyGrad.addColorStop(1, darkerColor || e.type.color);
        ctx.fillStyle = enemyGrad;
        ctx.beginPath();
        ctx.arc(e.x, e.y, e.type.size/2, 0, Math.PI*2);
        ctx.fill();

        // Brillo dinámico
        const highlightPulse = 0.2 + Math.sin(time * 3 + e.x) * 0.1;
        ctx.fillStyle = `rgba(255, 255, 255, ${highlightPulse})`;
        ctx.beginPath();
        ctx.arc(e.x - e.type.size/6, e.y - e.type.size/6, e.type.size/4, 0, Math.PI*2);
        ctx.fill();
        
        // Contorno sutil
        ctx.strokeStyle = 'rgba(0, 0, 0, 0.3)';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.arc(e.x, e.y, e.type.size/2, 0, Math.PI*2);
        ctx.stroke();

        // Barra de vida mejorada
        const barW = e.type.size + 4;
        ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
        ctx.fillRect(e.x - barW/2, e.y - e.type.size/2 - 12, barW, 6);
        const healthColor = e.hp / e.maxHp > 0.5 ? '#4CAF50' : e.hp / e.maxHp > 0.25 ? '#FFC107' : '#e74c3c';
        ctx.fillStyle = healthColor;
        ctx.fillRect(e.x - barW/2, e.y - e.type.size/2 - 12, barW * (e.hp / e.maxHp), 6);

        // BOSS REMASTERIZADO con efectos por fase y sistema de 20 bosses
        const isBoss = (e.type === ENEMY_TYPES.BOSS) || (e.bossNumber && e.type && e.type.size >= 65);
        if (isBoss) {
            const phase = e.visualPhase || e.currentPhaseIndex || 1;
            const bossPulse = 0.6 + Math.sin(time * 4) * 0.4;
            
            // Colores por fase
            let phaseColor = '#FFD700'; // Fase 1: Oro
            if (phase === 2) phaseColor = '#FF8C00'; // Fase 2: Naranja
            else if (phase === 3) phaseColor = '#9B59B6'; // Fase 3: Púrpura
            else if (phase === 4) phaseColor = '#E84393'; // Fase 4: Rosa (ENRAGE)
            
            const rgb = phase === 1 ? '255, 215, 0' : 
                        phase === 2 ? '255, 140, 0' :
                        phase === 3 ? '155, 89, 182' : '232, 67, 147';
            
            // Aura exterior más grande en fase 4
            const auraSize = phase === 4 ? 50 : 35;
            const bossAura = ctx.createRadialGradient(e.x, e.y, e.type.size/2, e.x, e.y, e.type.size/2 + auraSize);
            bossAura.addColorStop(0, 'rgba(' + rgb + ', 0)');
            bossAura.addColorStop(0.5, `rgba(${rgb}, ${bossPulse * (phase === 4 ? 0.3 : 0.15)})`);
            bossAura.addColorStop(1, 'rgba(' + rgb + ', 0)');
            ctx.fillStyle = bossAura;
            ctx.beginPath();
            ctx.arc(e.x, e.y, e.type.size/2 + auraSize, 0, Math.PI*2);
            ctx.fill();
            
            // Telegraph del siguiente ataque
            if (e.nextAttackTelegraph > 0) {
                const telegraphAlpha = e.nextAttackTelegraph;
                ctx.strokeStyle = `rgba(255, 50, 50, ${telegraphAlpha})`;
                ctx.lineWidth = 4;
                ctx.setLineDash([8, 4]);
                ctx.beginPath();
                ctx.arc(e.x, e.y, e.type.size/2 + 25, 0, Math.PI*2);
                ctx.stroke();
                ctx.setLineDash([]);
            }
            
            // Anillos múltiples con velocidad por fase
            const ringSpeed = phase * 1.5;
            ctx.strokeStyle = `rgba(${rgb}, ${bossPulse})`;
            ctx.lineWidth = phase >= 3 ? 6 : 5;
            ctx.beginPath();
            ctx.arc(e.x, e.y, e.type.size/2 + 8, 0, Math.PI*2);
            ctx.stroke();
            
            ctx.strokeStyle = `rgba(${rgb}, ${bossPulse * 0.5})`;
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.arc(e.x, e.y, e.type.size/2 + 18, 0, Math.PI*2);
            ctx.stroke();
            
            // Partículas orbitales (más en fase 4)
            const orbCount = phase === 4 ? 10 : 6;
            for (let i = 0; i < orbCount; i++) {
                const angle = (time * ringSpeed + i * Math.PI * 2 / orbCount) % (Math.PI * 2);
                const orbX = e.x + Math.cos(angle) * (e.type.size/2 + 14);
                const orbY = e.y + Math.sin(angle) * (e.type.size/2 + 14);
                ctx.fillStyle = `rgba(${rgb}, ${bossPulse})`;
                ctx.beginPath();
                ctx.arc(orbX, orbY, phase >= 3 ? 4 : 3, 0, Math.PI * 2);
                ctx.fill();
            }
            
            // Nombre del boss y tier
            ctx.save();
            if (e.name) {
                // Título del boss
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 18px Arial';
                ctx.textAlign = 'center';
                ctx.strokeStyle = 'rgba(0, 0, 0, 0.9)';
                ctx.lineWidth = 4;
                ctx.strokeText(e.name, e.x, e.y - e.type.size/2 - 60);
                ctx.fillText(e.name, e.x, e.y - e.type.size/2 - 60);
                
                // Subtítulo
                if (e.title) {
                    ctx.fillStyle = phaseColor;
                    ctx.font = '12px Arial';
                    ctx.strokeStyle = 'rgba(0, 0, 0, 0.8)';
                    ctx.lineWidth = 3;
                    ctx.strokeText(e.title, e.x, e.y - e.type.size/2 - 45);
                    ctx.fillText(e.title, e.x, e.y - e.type.size/2 - 45);
                }
                
                // Indicador de tier
                if (e.tier) {
                    const tierNames = ['', 'TERRITORIAL', 'BESTIA', 'PLANETARIA', 'CÓSMICA'];
                    const tierColors = ['', '#4CAF50', '#FF9800', '#9C27B0', '#F44336'];
                    ctx.fillStyle = tierColors[e.tier] || '#ffffff';
                    ctx.font = 'bold 10px Arial';
                    ctx.strokeStyle = 'rgba(0, 0, 0, 0.9)';
                    ctx.lineWidth = 2;
                    const tierText = `[${tierNames[e.tier]}]`;
                    ctx.strokeText(tierText, e.x, e.y - e.type.size/2 - 30);
                    ctx.fillText(tierText, e.x, e.y - e.type.size/2 - 30);
                }
            }
            
            // Indicador de fase (texto)
            ctx.fillStyle = phaseColor;
            ctx.font = 'bold 14px Arial';
            ctx.textAlign = 'center';
            ctx.strokeStyle = 'rgba(0, 0, 0, 0.8)';
            ctx.lineWidth = 3;
            const phaseText = phase === 4 ? 'ENRAGE!' : `Fase ${phase}`;
            const phaseY = e.name ? e.y - e.type.size/2 - 15 : e.y - e.type.size/2 - 30;
            ctx.strokeText(phaseText, e.x, phaseY);
            ctx.fillText(phaseText, e.x, phaseY);
            ctx.restore();
        }

        // Overlay: nombre del último patrón usado (para depurar que sí se usan)
        if (e._lastPattern) {
            ctx.save();
            ctx.globalAlpha = 0.6;
            ctx.fillStyle = '#ddd';
            ctx.font = '10px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(e._lastPattern, e.x, e.y - e.type.size/2 - 20);
            ctx.restore();
        }
    }

    // Jugador con sombra y brillo
    if (typeof player !== 'undefined') {
        // Sombra difusa
        ctx.save();
        ctx.shadowColor = 'rgba(0, 0, 0, 0.6)';
        ctx.shadowBlur = 12;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 8;
        ctx.fillStyle = '#1976D2';
        ctx.fillRect(player.x, player.y, player.w, player.h);
        ctx.restore();
        
        // Cuerpo principal con gradiente
        const playerGrad = ctx.createLinearGradient(player.x, player.y, player.x, player.y + player.h);
        playerGrad.addColorStop(0, '#29B5F6');
        playerGrad.addColorStop(1, '#1976D2');
        ctx.fillStyle = playerGrad;
        ctx.fillRect(player.x, player.y, player.w, player.h);
        
        // Brillo superior
        ctx.fillStyle = 'rgba(255, 255, 255, 0.35)';
        ctx.fillRect(player.x + 2, player.y + 2, player.w - 4, player.h / 3);
        
        // Contorno
        ctx.strokeStyle = '#0D47A1';
        ctx.lineWidth = 2;
        ctx.strokeRect(player.x, player.y, player.w, player.h);
        
        // Resplandor si tiene escudo
        if (player.effects && player.effects.shieldTimer > 0) {
            const shieldPulse = 0.5 + Math.sin(time * 8) * 0.3;
            ctx.strokeStyle = `rgba(100, 200, 255, ${shieldPulse})`;
            ctx.lineWidth = 3;
            ctx.strokeRect(player.x - 4, player.y - 4, player.w + 8, player.h + 8);
        }
    }

    // Balas jugador con efecto de estela
    for (let b of (typeof bullets !== 'undefined' ? bullets : [])) {
        // Resplandor
        const bulletGlow = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, b.r * 3);
        bulletGlow.addColorStop(0, 'rgba(200, 200, 200, 0.8)');
        bulletGlow.addColorStop(1, 'rgba(200, 200, 200, 0)');
        ctx.fillStyle = bulletGlow;
        ctx.beginPath();
        ctx.arc(b.x, b.y, b.r * 3, 0, Math.PI * 2);
        ctx.fill();
        
        // Núcleo
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.fillStyle = '#d3d3d3';
        ctx.beginPath();
        ctx.arc(b.x, b.y, b.r * 0.6, 0, Math.PI * 2);
        ctx.fill();
    }

    // Balas enemigas con estilos por shape/color
    for (let b of (typeof enemyBullets !== 'undefined' ? enemyBullets : [])) {
        const col = b.color || '#a63a3aff';
        const shape = b.shape || 'orb';
        ctx.save();
        switch (shape) {
            case 'beam_short': {
                // Línea corta roja tipo rayo ocular
                const len = 16;
                const ang = Math.atan2(b.vy, b.vx);
                ctx.strokeStyle = col;
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.moveTo(b.x - Math.cos(ang)*len*0.5, b.y - Math.sin(ang)*len*0.5);
                ctx.lineTo(b.x + Math.cos(ang)*len*0.5, b.y + Math.sin(ang)*len*0.5);
                ctx.stroke();
                break;
            }
            case 'zigzag': {
                ctx.strokeStyle = col;
                ctx.lineWidth = 2;
                ctx.beginPath();
                const ang = Math.atan2(b.vy, b.vx);
                const perp = ang + Math.PI/2;
                const seg = 4, amp = 4;
                ctx.moveTo(b.x, b.y);
                for (let i=1;i<=4;i++) {
                    const t = i/4;
                    const px = b.x + Math.cos(ang)*t*18 + Math.cos(perp)*((i%2?1:-1)*amp);
                    const py = b.y + Math.sin(ang)*t*18 + Math.sin(perp)*((i%2?1:-1)*amp);
                    ctx.lineTo(px, py);
                }
                ctx.stroke();
                break;
            }
            case 'dagger':
            case 'needle':
            case 'slash': {
                const ang = Math.atan2(b.vy, b.vx);
                ctx.translate(b.x, b.y);
                ctx.rotate(ang);
                ctx.fillStyle = col;
                ctx.beginPath();
                ctx.moveTo(-6, -2);
                ctx.lineTo(6, 0);
                ctx.lineTo(-6, 2);
                ctx.closePath();
                ctx.fill();
                break;
            }
            case 'shard': {
                ctx.fillStyle = col;
                ctx.beginPath();
                ctx.moveTo(b.x-3, b.y-5);
                ctx.lineTo(b.x+3, b.y);
                ctx.lineTo(b.x-2, b.y+5);
                ctx.closePath();
                ctx.fill();
                break;
            }
            case 'diamond': {
                ctx.translate(b.x, b.y);
                ctx.rotate(Math.PI/4);
                ctx.fillStyle = col;
                ctx.fillRect(-3, -3, 6, 6);
                break;
            }
            case 'spark': {
                ctx.strokeStyle = col; ctx.lineWidth = 2;
                ctx.beginPath(); ctx.moveTo(b.x-4, b.y); ctx.lineTo(b.x+4, b.y); ctx.stroke();
                ctx.beginPath(); ctx.moveTo(b.x, b.y-4); ctx.lineTo(b.x, b.y+4); ctx.stroke();
                break;
            }
            case 'orb_tail': {
                const ang = Math.atan2(b.vy, b.vx);
                ctx.fillStyle = col;
                ctx.beginPath(); ctx.arc(b.x, b.y, b.r||5, 0, Math.PI*2); ctx.fill();
                ctx.strokeStyle = col; ctx.globalAlpha = 0.5; ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(b.x, b.y);
                ctx.lineTo(b.x - Math.cos(ang)*12, b.y - Math.sin(ang)*12);
                ctx.stroke();
                break;
            }
            case 'lob': {
                ctx.fillStyle = col;
                ctx.beginPath(); ctx.arc(b.x, b.y, (b.r||5), 0, Math.PI*2); ctx.fill();
                ctx.globalAlpha = 0.25; ctx.fillRect(b.x-2, b.y+6, 4, 2);
                break;
            }
            case 'drop_light': {
                ctx.fillStyle = col;
                ctx.beginPath();
                ctx.moveTo(b.x, b.y-6);
                ctx.quadraticCurveTo(b.x+3, b.y, b.x, b.y+6);
                ctx.quadraticCurveTo(b.x-3, b.y, b.x, b.y-6);
                ctx.fill();
                break;
            }
            case 'orb_glow': {
                const g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, (b.r||6)*2);
                g.addColorStop(0, col);
                g.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = g;
                ctx.beginPath(); ctx.arc(b.x, b.y, (b.r||6)*2, 0, Math.PI*2); ctx.fill();
                ctx.fillStyle = col;
                ctx.beginPath(); ctx.arc(b.x, b.y, b.r||6, 0, Math.PI*2); ctx.fill();
                break;
            }
            case 'wisp': {
                ctx.globalAlpha = 0.7; ctx.fillStyle = col;
                ctx.beginPath(); ctx.arc(b.x, b.y, b.r||5, 0, Math.PI*2); ctx.fill();
                ctx.globalAlpha = 0.25; ctx.beginPath(); ctx.arc(b.x, b.y, (b.r||5)*1.8, 0, Math.PI*2); ctx.fill();
                break;
            }
            case 'ball_fire':
            case 'orb':
            default: {
                ctx.fillStyle = col;
                ctx.beginPath();
                ctx.arc(b.x, b.y, b.r || 5, 0, Math.PI*2);
                ctx.fill();
                break;
            }
        }
        ctx.restore();
    }

    // Efecto de transición de habitación mejorado con partículas
    {
        const rt = (typeof roomTransition === 'number' && isFinite(roomTransition))
            ? Math.max(0, Math.min(1, roomTransition)) : 0;
        if (rt > 0.001) {
            // Fade base
            ctx.fillStyle = `rgba(10, 10, 20, ${rt * 0.9})`;
            ctx.fillRect(0, 0, CONFIG.CANVAS_W, CONFIG.CANVAS_H);
            
            // Efecto de barrido radial
            const centerX = CONFIG.CANVAS_W / 2;
            const centerY = CONFIG.CANVAS_H / 2;
            const maxRad = Math.sqrt(centerX * centerX + centerY * centerY);
            const sweepGrad = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, maxRad * (1 - rt));
            sweepGrad.addColorStop(0, 'rgba(243, 156, 18, 0)');
            sweepGrad.addColorStop(0.8, `rgba(243, 156, 18, ${rt * 0.15})`);
            sweepGrad.addColorStop(1, `rgba(243, 156, 18, ${rt * 0.3})`);
            ctx.fillStyle = sweepGrad;
            ctx.fillRect(0, 0, CONFIG.CANVAS_W, CONFIG.CANVAS_H);
            
            // Partículas flotantes durante transición
            if (!window._transitionParticles || rt > 0.95) {
                window._transitionParticles = [];
                for (let i = 0; i < 40; i++) {
                    window._transitionParticles.push({
                        x: Math.random() * CONFIG.CANVAS_W,
                        y: Math.random() * CONFIG.CANVAS_H,
                        vx: (Math.random() - 0.5) * 100,
                        vy: (Math.random() - 0.5) * 100,
                        life: 1
                    });
                }
            }
            if (window._transitionParticles) {
                for (let p of window._transitionParticles) {
                    p.life -= 0.02;
                    if (p.life > 0) {
                        ctx.fillStyle = `rgba(243, 156, 18, ${p.life * rt})`;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, 3, 0, Math.PI * 2);
                        ctx.fill();
                    }
                }
            }
            
            // Fallback
            if (typeof roomTransition === 'number') {
                roomTransition = Math.max(0, roomTransition - 0.04);
            }
        }
    }

    // Player trail (estela de movimiento)
    drawPlayerTrail();
    
    // Partículas (explosiones, efectos)
    drawParticles();
    
    // Restore canvas after shake
    ctx.restore();
    
    // Sala de descanso removida
}
</script>