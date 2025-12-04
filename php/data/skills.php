<?php
// skills.php - Sistema de habilidades mejorado
$SKILLS = [
    // Habilidades de movilidad (Tecla Q)
    'dash' => [
        'name' => 'Dash',
        'description' => 'Dash rápido que atraviesa enemigos',
        'type' => 'active',
        'cooldown' => 2.5,
        'duration' => 0.25,
        'effect' => 'dash',
        'key' => 'Q',
        'color' => '#29B5F6',
        'speedMultiplier' => 6,
        'invulnerable' => true
    ],
    'teleport' => [
        'name' => 'Teletransporte',
        'description' => 'Teletransporta hacia el cursor (300px)',
        'type' => 'active',
        'cooldown' => 8,
        'effect' => 'teleport',
        'key' => 'Q',
        'color' => '#9C27B0',
        'distance' => 300
    ],
    
    // Habilidades de curación (Tecla E)
    'heal' => [
        'name' => 'Curación',
        'description' => 'Recupera 15 HP instantáneamente',
        'type' => 'active',
        'cooldown' => 12,
        'effect' => 'heal',
        'key' => 'E',
        'color' => '#4CAF50',
        'amount' => 15
    ],
    'super_heal' => [
        'name' => 'Curación Mayor',
        'description' => 'Recupera 30 HP + regeneración',
        'type' => 'active',
        'cooldown' => 20,
        'effect' => 'super_heal',
        'key' => 'E',
        'color' => '#66BB6A',
        'amount' => 30,
        'regen' => 2,
        'regenDuration' => 5
    ],
    'life_drain' => [
        'name' => 'Drenar Vida',
        'description' => 'Absorbe 3 HP de cada enemigo cercano',
        'type' => 'active',
        'cooldown' => 15,
        'effect' => 'life_drain',
        'key' => 'E',
        'color' => '#E91E63',
        'radius' => 200,
        'drainAmount' => 3
    ],
    
    // Habilidades ofensivas (Tecla R)
    'rage' => [
        'name' => 'Furia',
        'description' => 'Daño x3 y velocidad x1.5 por 8s',
        'type' => 'active',
        'cooldown' => 18,
        'duration' => 8,
        'effect' => 'rage',
        'key' => 'R',
        'color' => '#FF5722',
        'damageMultiplier' => 3,
        'speedMultiplier' => 1.5
    ],
    'berserk' => [
        'name' => 'Berserker',
        'description' => 'Daño x2, vida -50%, velocidad x2 por 12s',
        'type' => 'active',
        'cooldown' => 25,
        'duration' => 12,
        'effect' => 'berserk',
        'key' => 'R',
        'color' => '#D32F2F',
        'damageMultiplier' => 2,
        'speedMultiplier' => 2,
        'healthCost' => 0.5
    ],
    'energy_burst' => [
        'name' => 'Explosión de Energía',
        'description' => 'Onda expansiva que daña y empuja',
        'type' => 'active',
        'cooldown' => 8,
        'effect' => 'energy_burst',
        'key' => 'R',
        'color' => '#FFC107',
        'damage' => 6,
        'radius' => 280,
        'knockback' => 450
    ],
    
    // Habilidades defensivas (Tecla F)
    'shield' => [
        'name' => 'Escudo',
        'description' => 'Inmune a daño por 4 segundos',
        'type' => 'active',
        'cooldown' => 20,
        'duration' => 4,
        'effect' => 'shield',
        'key' => 'F',
        'color' => '#03A9F4'
    ],
    'invisibility' => [
        'name' => 'Invisibilidad',
        'description' => 'Invisible y más rápido por 5s',
        'type' => 'active',
        'cooldown' => 16,
        'duration' => 5,
        'effect' => 'invisibility',
        'key' => 'F',
        'color' => '#9E9E9E',
        'speedMultiplier' => 1.3
    ],
    'time_stop' => [
        'name' => 'Detener Tiempo',
        'description' => 'Congela enemigos y balas por 3s',
        'type' => 'active',
        'cooldown' => 30,
        'duration' => 3,
        'effect' => 'time_stop',
        'key' => 'F',
        'color' => '#00BCD4'
    ],
    
    // Habilidades de control (Tecla C)
    'gravity_well' => [
        'name' => 'Pozo Gravitacional',
        'description' => 'Atrae y ralentiza enemigos por 6s',
        'type' => 'active',
        'cooldown' => 18,
        'duration' => 6,
        'effect' => 'gravity_well',
        'key' => 'C',
        'color' => '#673AB7',
        'radius' => 180,
        'pullStrength' => 150,
        'slowAmount' => 0.5
    ],
    
    // Pasivas permanentes
    'health_boost' => [
        'name' => 'Vida Extra',
        'description' => 'HP máximo +20 permanente',
        'type' => 'passive',
        'effect' => 'health_boost',
        'color' => '#8BC34A',
        'amount' => 20
    ],
    'speed_boost' => [
        'name' => 'Velocidad Extra',
        'description' => 'Velocidad +30% permanente',
        'type' => 'passive',
        'effect' => 'speed_boost',
        'color' => '#00E676',
        'multiplier' => 1.3
    ],
    'damage_boost' => [
        'name' => 'Más Daño',
        'description' => 'Daño de balas +1 permanente',
        'type' => 'passive',
        'effect' => 'damage_boost',
        'color' => '#FF6F00',
        'amount' => 1
    ],
    'fire_rate_boost' => [
        'name' => 'Cadencia Rápida',
        'description' => 'Dispara 40% más rápido',
        'type' => 'passive',
        'effect' => 'fire_rate_boost',
        'color' => '#FFD600',
        'multiplier' => 0.6
    ],
    'critical_hit' => [
        'name' => 'Golpe Crítico',
        'description' => '5% de probabilidad de golpe crítico (x2 daño)',
        'type' => 'passive',
        'effect' => 'critical_hit',
        'color' => '#E040FB',
        'critChance' => 0.05,
        'critMultiplier' => 2
    ],
    'explosive_bullets' => [
        'name' => 'Balas Explosivas',
        'description' => 'Las balas explotan al impactar, dañando en área',
        'type' => 'passive',
        'effect' => 'explosive_bullets',
        'color' => '#FF3D00',
        'explosionRadius' => 50,
        'explosionDamage' => 4
    ],
    'life_steal' => [
        'name' => 'Robo de Vida',
        'description' => 'Roba 2 HP por cada enemigo derrotado',
        'type' => 'passive',
        'effect' => 'life_steal',
        'color' => '#D500F9',
        'stealAmount' => 2
    ],
    'grenade_launcher' => [
        'name' => 'Lanzagranadas',
        'description' => 'Las balas tienen 10% de probabilidad de explotar en granada',
        'type' => 'passive',
        'effect' => 'grenade_launcher',
        'color' => '#FF1744',
        'grenadeChance' => 0.1,
        'grenadeRadius' => 70,
        'grenadeDamage' => 8
    ],
    'summon_turret' => [
        'name' => 'Torreta de Asistencia',
        'description' => 'Invoca una torreta que dispara a los enemigos cercanos',
        'type' => 'passive',
        'effect' => 'summon_turret',
        'color' => '#2979FF',
        'turretDuration' => 20,
        'turretFireRate' => 1,
        'turretDamage' => 5
    ],
    'shockwave' => [
        'name' => 'Onda de Choque',
        'description' => 'Al recibir daño, libera una onda que aturde enemigos cercanos',
        'type' => 'passive',
        'effect' => 'shockwave',
        'color' => '#00C853',
        'radius' => 100,
        'stunDuration' => 1.5
    ]
];
?>
