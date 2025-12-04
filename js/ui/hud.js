// HUD HTML markup
const hudHTML = `
<div class="stats" aria-live="polite">
    <div class="stat-row">
        <div class="label">Vida</div>
        <div class="health-wrap">
            <div class="health-bar" aria-hidden="true"><div id="healthFill" class="health-fill"></div></div>
            <div id="healthText" class="health-text">10</div>
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
`;

// Update UI function
function updateUI() {
    const hf = document.getElementById('healthFill');
    const ht = document.getElementById('healthText');
    if (hf && typeof player !== 'undefined') {
        const maxBar = Math.max(CONFIG.MAX_HEALTH, 100);
        const pct = Math.max(0, Math.min(1, player.health / maxBar));
        
        // Smooth animation
        const currentWidth = parseFloat(hf.style.width) || 100;
        const targetWidth = pct * 100;
        const newWidth = currentWidth + (targetWidth - currentWidth) * 0.15;
        hf.style.width = newWidth + '%';
        
        // Color change based on health
        if (pct > 0.6) {
            hf.style.background = 'linear-gradient(90deg, #4CAF50, #66BB6A)';
        } else if (pct > 0.3) {
            hf.style.background = 'linear-gradient(90deg, #FFC107, #FFB300)';
        } else {
            hf.style.background = 'linear-gradient(90deg, #e74c3c, #c0392b)';
            hf.style.animation = 'healthPulse 0.5s infinite';
        }
        
        // Flash on damage
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
        r.textContent = currentRoom;
    }
    const k = document.getElementById('kills');
    if (k) k.textContent = (typeof totalKills !== 'undefined' ? totalKills : 0);
}
