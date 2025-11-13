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
    
    const damageBonus = player.passiveDamage || 0;
    const damageMult = player.effects.damageMultiplier || 1;
    
    bullets.push({
        x: player.x + player.w/2,
        y: player.y + player.h/2,
        vx: dx * CONFIG.BULLET_SPEED,
        vy: dy * CONFIG.BULLET_SPEED,
        r: 6,
        damage: (1 + damageBonus) * damageMult
    });
    
    player.shootCooldown = 0.25;
}
