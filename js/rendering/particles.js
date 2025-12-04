// Particle System
window.particles = [];
window.particlePool = [];
window.maxParticles = 500;

// Create explosion effect
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

// Create heal effect
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

// Create teleport effect
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

// Create burst effect
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

// Create drain line effect
function createDrainLine(fromX, fromY, toX, toY) {
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

// Update particles
function updateParticles(dt) {
    for (let i = window.particles.length - 1; i >= 0; i--) {
        const p = window.particles[i];
        p.x += p.vx * dt;
        p.y += p.vy * dt;
        p.vy += (p.gravity || 0) * dt;
        p.vx *= 0.97;
        const decay = (p.decayMult || 2);
        p.life -= dt * decay;
        if (p.life <= 0) {
            window.particles.splice(i, 1);
        }
    }
}

// Draw particles
function drawParticles() {
    for (let p of window.particles) {
        const alpha = Math.max(0, Math.min(1, p.life / (p.maxLife || 1)));
        ctx.save();
        if (p.blend === 'lighter') ctx.globalCompositeOperation = 'lighter';
        ctx.globalAlpha = alpha;
        
        const pr = Math.max(1, p.size || 2);
        const px = Number(p.x);
        const py = Number(p.y);
        const safePr = Number(pr);
        
        if (!isFinite(px) || !isFinite(py) || !isFinite(safePr)) {
            if (isFinite(px) && isFinite(py)) {
                ctx.fillStyle = (p.color || '#fff');
                ctx.beginPath();
                ctx.arc(px, py, 2, 0, Math.PI*2);
                ctx.fill();
            }
            ctx.restore();
            continue;
        }
        
        try {
            const grad = ctx.createRadialGradient(px, py, 0, px, py, safePr * 3);
            grad.addColorStop(0, p.color);
            grad.addColorStop(0.4, p.color);
            grad.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.fillStyle = grad;
            ctx.beginPath();
            ctx.arc(px, py, safePr * 3, 0, Math.PI * 2);
            ctx.fill();
            
            ctx.globalAlpha = alpha;
            ctx.fillStyle = p.color;
            ctx.beginPath();
            ctx.arc(px, py, safePr, 0, Math.PI * 2);
            ctx.fill();
        } catch (err) {
            ctx.fillStyle = (p.color || '#fff');
            ctx.beginPath();
            ctx.arc(px, py, Math.max(1, safePr), 0, Math.PI*2);
            ctx.fill();
        }
        ctx.restore();
    }
}
