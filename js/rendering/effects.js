// Visual Effects System
// Screen shake
window.screenShake = { x: 0, y: 0, intensity: 0, duration: 0 };

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

// Chromatic Aberration
window.chromaticEffect = { active: false, intensity: 0 };

function triggerChromatic(intensity, duration) {
    window.chromaticEffect.active = true;
    window.chromaticEffect.intensity = intensity;
    window.chromaticEffect.duration = duration;
}

function updateChromaticEffect(dt) {
    if (window.chromaticEffect && window.chromaticEffect.active) {
        if (window.chromaticEffect.duration > 0) {
            window.chromaticEffect.duration -= dt;
            window.chromaticEffect.intensity *= 0.95;
        } else {
            window.chromaticEffect.active = false;
            window.chromaticEffect.intensity = 0;
        }
    }
}

// Bloom effect
window.bloomEffect = { active: true, intensity: 0.3 };

// Time distortion
window.timeDistortion = { active: false, amount: 0 };

function triggerTimeDistortion(amount, duration) {
    window.timeDistortion.active = true;
    window.timeDistortion.amount = amount;
    window.timeDistortion.duration = duration;
}

// Slow motion
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

// Background parallax layers
window.bgLayers = [];

function initBackgroundLayers() {
    if (window.bgLayers.length > 0) return;
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

// Update all effects
function updateEffects(dt) {
    updateScreenShake(dt);
    updateChromaticEffect(dt);
    updateSlowMo(dt);
    updatePlayerTrail(dt);
}
