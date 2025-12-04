// controls.js - Input handling
// Expects globals: keys, player, shoot, CONFIG

function initControls() {
    window.addEventListener('keydown', e => {
        const k = e.key.toLowerCase();
        if (k === 'a') keys.left = true;
        if (k === 'd') keys.right = true;
        if (k === 'w') keys.up = true;
        if (k === 's') keys.down = true;

        // Disparar con flechas
        if (e.key === 'ArrowUp') shoot(0, -1);
        if (e.key === 'ArrowDown') shoot(0, 1);
        if (e.key === 'ArrowLeft') shoot(-1, 0);
        if (e.key === 'ArrowRight') shoot(1, 0);
        
        // Activar skills
        if (typeof activateSkill === 'function' && typeof activeSkills !== 'undefined') {
            if (k === 'q' && activeSkills['Q']) activateSkill(activeSkills['Q']);
            if (k === 'e' && activeSkills['E']) activateSkill(activeSkills['E']);
            if (k === 'r' && activeSkills['R']) activateSkill(activeSkills['R']);
            if (k === 'f' && activeSkills['F']) activateSkill(activeSkills['F']);
            if (k === 'c' && activeSkills['C']) activateSkill(activeSkills['C']);
        }

        e.preventDefault();
    });

    window.addEventListener('keyup', e => {
        const k = e.key.toLowerCase();
        if (k === 'a') keys.left = false;
        if (k === 'd') keys.right = false;
        if (k === 'w') keys.up = false;
        if (k === 's') keys.down = false;
    });
}

function shoot(dx, dy) {
    if (player.shootCooldown > 0) return;
    // Actualizar la dirección en la que mira el jugador según la dirección del disparo
    if (typeof player !== 'undefined') {
        if (dx === 0 && dy === 0) {
            // no change
        } else if (Math.abs(dx) > Math.abs(dy)) {
            player.facing = (dx > 0) ? 'right' : 'left';
        } else {
            player.facing = (dy > 0) ? 'down' : 'up';
        }
    }
    
    const damageBonus = player.passiveDamage || 0;
    const damageMult = player.effects.damageMultiplier || 1;
    
    bullets.push({
        x: player.x + player.w/2,
        y: player.y + player.h/2,
        vx: dx * CONFIG.BULLET_SPEED,
        vy: dy * CONFIG.BULLET_SPEED,
        r: 6,
        damage: (1 + damageBonus) * damageMult,
        color: '#ffffff',
        glow: true,
        trailColor: '#9fd8ff'
    });
    // muzzle flash / trail
    if (typeof window !== 'undefined' && window.particles) {
        const bx = player.x + player.w/2, by = player.y + player.h/2;
        for (let i=0;i<6;i++) {
            const ang = Math.atan2(dy, dx) + (Math.random()-0.5)*1.2;
            const sp = 120 + Math.random()*120;
            window.particles.push({ x: bx, y: by, vx: Math.cos(ang)*sp, vy: Math.sin(ang)*sp, life:0.25, maxLife:0.25, size:2 + Math.random()*3, color: '#ffffff', blend: 'lighter' });
        }
    }
    
    player.shootCooldown = 0.25;
}
