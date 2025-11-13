<?php
// Configuración / metadatos de patrones de ataque exportados a JS
// patrones de ataque / metadatos
$ATTACKS = [
    'circle' => ['name' => 'circle', 'count' => 12, 'speed' => 140, 'color' => '#ffce54', 'shape' => 'orb'],
    'spiral' => ['name' => 'spiral', 'bulletsPerWave' => 6, 'speed' => 160, 'color' => '#b8e986', 'shape' => 'orb_glow'],
    'aimed' => ['name' => 'aimed', 'spread' => 0.3, 'speed' => 220, 'color' => '#ff6b6b', 'shape' => 'small'],
    'random' => ['name' => 'random', 'count' => 5, 'speed' => 180, 'color' => '#c6c6c6', 'shape' => 'spark'],
    'burst' => ['name' => 'burst', 'waves' => 3, 'count' => 8, 'speed' => 200, 'color' => '#4ecdc4', 'shape' => 'diamond'],
    'laser' => ['name' => 'laser', 'duration' => 2],
    'homing' => ['name' => 'homing', 'count' => 4],

    // Patrones especiales usados por enemigos concretos
    
    // SLIME - Divide al morir
    'random' => ['name' => 'random', 'count' => 5, 'speed' => 140, 'spread' => 1.5],
    'slime_split' => ['name' => 'slime_split', 'effect' => 'split', 'count' => 2, 'color' => '#6ab04c', 'shape' => 'orb'],
    
    // SPIDER - Telarañas y veneno
    'spider_white' => ['name' => 'spider_white', 'effect' => 'slow', 'speed' => 120, 'count' => 6, 'slowAmount' => 0.6, 'duration' => 1.5, 'color' => '#dfe6e9', 'shape' => 'web_proj'],
    'spider_green' => ['name' => 'spider_green', 'effect' => 'poison', 'speed' => 110, 'count' => 3, 'damage' => 1, 'dotDuration' => 3, 'tick' => 0.5, 'color' => '#2ecc71', 'shape' => 'toxic'],
    
    // SKELETON - Huesos que rebotan
    'bone_throw' => ['name' => 'bone_throw', 'speed' => 180, 'count' => 3, 'bounce' => 2, 'color' => '#ecf0f1', 'shape' => 'bone'],
    
    // EYE - Rayos que siguen al jugador
    'homing' => ['name' => 'homing', 'speed' => 150, 'count' => 3, 'tracking' => 0.05, 'color' => '#9b59b6', 'shape' => 'orb_tail'],
    'eye_beam' => ['name' => 'eye_beam', 'speed' => 280, 'count' => 1, 'laser' => true, 'color' => '#ff2d55', 'shape' => 'beam_short'],
    
    // ICE - Congela y ralentiza
    'ice_frag' => ['name' => 'ice_frag', 'effect' => 'slow', 'speed' => 130, 'count' => 8, 'slowAmount' => 0.5, 'duration' => 2.0, 'color' => '#74b9ff', 'shape' => 'shard'],
    'freeze_nova' => ['name' => 'freeze_nova', 'effect' => 'freeze', 'speed' => 100, 'count' => 12, 'duration' => 1.0, 'color' => '#a5d8ff', 'shape' => 'nova'],
    
    // FIRE - Llamas y explosiones
    'flame_wave' => ['name' => 'flame_wave', 'speed' => 160, 'count' => 8, 'spread' => 0.4, 'color' => '#e74c3c', 'shape' => 'flame'],
    'fire_pool' => ['name' => 'fire_pool', 'effect' => 'burn', 'duration' => 4.0, 'damage' => 1, 'tick' => 0.5, 'color' => '#ff8c42', 'shape' => 'zone_fire'],
    
    // THUNDER - Cadenas eléctricas
    'chain_lightning' => ['name' => 'chain_lightning', 'speed' => 280, 'count' => 1, 'chain' => 3, 'color' => '#f1c40f', 'shape' => 'zigzag'],
    'thunder_storm' => ['name' => 'thunder_storm', 'speed' => 200, 'count' => 5, 'random' => true, 'color' => '#ffe066', 'shape' => 'drop_light'],
    
    // GHOST - Atraviesa paredes, se vuelve invulnerable
    'ghost_blast' => ['name' => 'ghost_blast', 'speed' => 140, 'count' => 8, 'phase' => true, 'color' => '#95a5a6', 'shape' => 'wisp'],
    'shadow_invuln' => ['name' => 'shadow_invuln', 'effect' => 'invuln', 'invulnDuration' => 1.2, 'chance' => 0.12, 'color' => '#7f8c8d', 'shape' => 'pulse'],
    
    // ARMOR - Disparos pesados que atraviesan
    'heavy_shot' => ['name' => 'heavy_shot', 'speed' => 100, 'count' => 3, 'size' => 12, 'pierce' => true, 'color' => '#7f8c8d', 'shape' => 'chunk'],
    'shield_bash' => ['name' => 'shield_bash', 'effect' => 'knockback', 'melee' => true, 'color' => '#bdc3c7', 'shape' => 'impact'],
    
    // MIMIC - Ataque sorpresa en melee
    'bite' => ['name' => 'bite', 'speed' => 0, 'melee' => true, 'damage' => 2, 'color' => '#d35400', 'shape' => 'impact'],
    'tongue_lash' => ['name' => 'tongue_lash', 'speed' => 220, 'count' => 4, 'aimed' => true, 'color' => '#e67e22', 'shape' => 'needle'],
    
    // GOBLIN - Lanza objetos
    'throw' => ['name' => 'throw', 'speed' => 200, 'count' => 2, 'gravity' => true, 'color' => '#27ae60', 'shape' => 'lob'],
    'dagger_rain' => ['name' => 'dagger_rain', 'speed' => 180, 'count' => 6, 'spread' => 1.0, 'color' => '#2ecc71', 'shape' => 'dagger'],
    
    // CULTIST - Invoca enemigos
    'summon' => ['name' => 'summon', 'spawn' => 'SLIME', 'max' => 2, 'color' => '#8e44ad', 'shape' => 'rune'],
    'dark_ritual' => ['name' => 'dark_ritual', 'speed' => 120, 'count' => 16, 'spiral' => true, 'color' => '#9b59b6', 'shape' => 'orb_dark'],
    
    // MUSHROOM - Esporas venenosas
    'spore' => ['name' => 'spore', 'effect' => 'poison', 'speed' => 80, 'count' => 8, 'dotDuration' => 2, 'tick' => 0.5, 'color' => '#e67e22', 'shape' => 'spore'],
    'spore_cloud' => ['name' => 'spore_cloud', 'effect' => 'poison_cloud', 'duration' => 5.0, 'radius' => 40, 'color' => '#e67e22', 'shape' => 'cloud'],
    
    // BABY_DRAGON - Aliento de fuego
    'fire_breath' => ['name' => 'fire_breath', 'speed' => 180, 'count' => 10, 'cone' => 0.6, 'color' => '#e84393', 'shape' => 'cone_flame'],
    'fireball' => ['name' => 'fireball', 'speed' => 160, 'count' => 1, 'explosion' => true, 'radius' => 30, 'color' => '#ff5252', 'shape' => 'ball_fire'],
    
    // BABY_GRIFFIN - Ataques aéreos
    'swoop' => ['name' => 'swoop', 'speed' => 240, 'count' => 3, 'dive' => true, 'color' => '#f39c12', 'shape' => 'slash'],
    'beak_peck' => ['name' => 'beak_peck', 'melee' => true, 'damage' => 1, 'rapid' => true, 'color' => '#f1c40f', 'shape' => 'impact'],
    'talon_strike' => ['name' => 'talon_strike', 'speed' => 200, 'count' => 4, 'spread' => 0.8, 'color' => '#ffda79', 'shape' => 'slash'],
    
    // SNAKE - Veneno y enrollarse
    'venom_spit' => ['name' => 'venom_spit', 'effect' => 'poison', 'speed' => 160, 'count' => 2, 'dotDuration' => 4, 'tick' => 0.5, 'color' => '#2ecc71', 'shape' => 'toxic_drop'],
    'coil' => ['name' => 'coil', 'effect' => 'trap', 'duration' => 2.0, 'slow' => 0.8, 'color' => '#27ae60', 'shape' => 'zone_trap'],
    
    // === BOSS REMASTERIZADO - 4 FASES ===
    // Fase 1 (100%-75% HP): Círculos básicos + ráfagas
    'boss_phase1' => ['name' => 'boss_phase1', 'speed' => 160, 'count' => 16, 'pattern' => 'circle', 'color' => '#c0392b', 'shape' => 'orb'],
    'boss_burst1' => ['name' => 'boss_burst1', 'speed' => 180, 'count' => 8, 'pattern' => 'burst', 'color' => '#e74c3c', 'shape' => 'diamond'],
    
    // Fase 2 (75%-50% HP): Espirales + proyectiles guiados
    'boss_phase2' => ['name' => 'boss_phase2', 'speed' => 170, 'count' => 20, 'pattern' => 'spiral', 'color' => '#e67e22', 'shape' => 'orb_tail'],
    'boss_homing2' => ['name' => 'boss_homing2', 'speed' => 140, 'count' => 6, 'tracking' => 0.04, 'color' => '#f39c12', 'shape' => 'orb_glow'],
    
    // Fase 3 (50%-25% HP): Barrages rápidas + laser sweep
    'boss_phase3' => ['name' => 'boss_phase3', 'speed' => 200, 'count' => 24, 'pattern' => 'barrage', 'color' => '#9b59b6', 'shape' => 'shard'],
    'boss_laser3' => ['name' => 'boss_laser3', 'speed' => 220, 'count' => 3, 'pattern' => 'laser_sweep', 'color' => '#8e44ad', 'shape' => 'beam_short'],
    
    // Fase 4 (25%-0% HP): ENRAGE - Todo mezclado + trampas adicionales
    'boss_phase4' => ['name' => 'boss_phase4', 'speed' => 180, 'count' => 30, 'pattern' => 'chaos', 'color' => '#e84393', 'shape' => 'spark'],
    'boss_enrage' => ['name' => 'boss_enrage', 'speed' => 240, 'count' => 12, 'pattern' => 'aimed_spread', 'color' => '#ff2d55', 'shape' => 'dagger']
];

// backward-compatible helper: if someone includes this file directly, return true
return true;
?>
