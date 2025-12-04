<?php
// enemies.php
// Centraliza tipos de enemigos y patrones de ataque (ahora ATTACKS está aquí)
// Esto permite editar todo lo relacionado con enemigos/ataques en un solo archivo.
// Definición de tipos de enemigos solicitada
$ENEMY_TYPES = [
	// Ejércitos básicos con habilidades únicas - REBALANCEADOS
	'SLIME'        => ['color' => '#6ab04c', 'size' => 18, 'hp' => 2, 'shootInterval' => 1.8, 'attacks' => ['random', 'circle']],
	'SPIDER'       => ['color' => '#b7950b', 'size' => 16, 'hp' => 3, 'shootInterval' => 1.6, 'attacks' => ['spider_white', 'spider_green']],
	'SKELETON'     => ['color' => '#ecf0f1', 'size' => 20, 'hp' => 3, 'shootInterval' => 1.8, 'attacks' => ['bone_throw', 'burst']],
	'EYE'          => ['color' => '#9b59b6', 'size' => 14, 'hp' => 2, 'shootInterval' => 1.4, 'attacks' => ['homing', 'eye_beam']],
	'ICE'          => ['color' => '#74b9ff', 'size' => 18, 'hp' => 2, 'shootInterval' => 2.0, 'attacks' => ['ice_frag', 'freeze_nova']],
	'FIRE'         => ['color' => '#e74c3c', 'size' => 18, 'hp' => 2, 'shootInterval' => 1.6, 'attacks' => ['flame_wave', 'fire_pool']],
	'THUNDER'      => ['color' => '#f1c40f', 'size' => 20, 'hp' => 3, 'shootInterval' => 1.5, 'attacks' => ['chain_lightning', 'thunder_storm']],
	// Ghost tiene ataque normal y ocasionalmente puede volverse invulnerable (shadow_invuln)
	'GHOST'        => ['color' => '#95a5a6', 'size' => 22, 'hp' => 2, 'shootInterval' => 2.2, 'attacks' => ['ghost_blast', 'shadow_invuln']],
	'ARMOR'        => ['color' => '#7f8c8d', 'size' => 28, 'hp' => 6, 'shootInterval' => 2.5, 'attacks' => ['heavy_shot', 'shield_bash']],
	'MIMIC'        => ['color' => '#d35400', 'size' => 24, 'hp' => 4, 'shootInterval' => 1.8, 'attacks' => ['tongue_lash', 'bite']],
	'GOBLIN'       => ['color' => '#27ae60', 'size' => 16, 'hp' => 2, 'shootInterval' => 1.6, 'attacks' => ['throw', 'dagger_rain']],
	'CULTIST'      => ['color' => '#8e44ad', 'size' => 18, 'hp' => 3, 'shootInterval' => 2.0, 'attacks' => ['summon', 'dark_ritual']],
	'MUSHROOM'     => ['color' => '#e67e22', 'size' => 16, 'hp' => 1, 'shootInterval' => 1.4, 'attacks' => ['spore', 'spore_cloud']],
	'BABY_DRAGON'  => ['color' => '#e84393', 'size' => 26, 'hp' => 4, 'shootInterval' => 1.6, 'attacks' => ['fire_breath', 'fireball']],
	'BABY_GRIFFIN' => ['color' => '#f39c12', 'size' => 24, 'hp' => 4, 'shootInterval' => 1.6, 'attacks' => ['swoop', 'talon_strike']],
	'SNAKE'        => ['color' => '#2ecc71', 'size' => 14, 'hp' => 1, 'shootInterval' => 1.2, 'attacks' => ['venom_spit', 'coil']],
	// Enemigos avanzados
	'WYVERN'       => ['color' => '#d91e18', 'size' =>  30, 'hp' => 8, 'shootInterval' => 1.5, 'attacks' => ['acid_spit','wing_buffet']],
	'TROLL'        => ['color' => '#27ae60', 'size' =>  32, 'hp' => 10, 'shootInterval' => 2.8, 'attacks' => ['rock_throw','regenerate']],
	'LICH'         => ['color' => '#8e44ad', 'size' => 28, 'hp' => 9, 'shootInterval' => 2.2, 'attacks' => ['dark_bolt','summon_skeletons']],
	'OGRE'         => ['color' => '#c0392b', 'size' =>  34, 'hp' => 12, 'shootInterval' => 3.0, 'attacks' => ['smash','stomp']],
	'VAMPIRE'      => ['color' => '#2c3e50', 'size' => 26, 'hp' => 7, 'shootInterval' => 1.8, 'attacks' => ['blood_suck','bat_swarm']],
	'DEMON'        => ['color' => '#e74c3c', 'size' => 36, 'hp' => 15, 'shootInterval' => 2.5, 'attacks' => ['fireball','hellfire']],
	'GOLEM'        => ['color' => '#7f8c8d', 'size' => 40, 'hp' => 20, 'shootInterval' => 3.5, 'attacks' => ['rock_throw','earthquake']],
	// Enemigo especial - jefe menor
	'MINI_BOSS'    => ['color' => '#d35400', 'size' => 40, 'hp' => 25, 'shootInterval' => 2.0, 'attacks' => ['mini_boss_special','power_strike'], 'is_boss' => true],

	// Boss REMASTERIZADO - NERFEADO pero con FASES VISUALES
	'BOSS'         => ['color' => '#c0392b', 'size' => 70, 'hp' => 30, 'shootInterval' => 1.2, 'attacks' => ['boss_phase1','boss_phase2','boss_phase3','boss_phase4'], 'is_boss' => true, 'phases' => 4]
];

// Backwards compatibility: aliasar algunas claves que el JS anterior pudo usar
$ENEMY_TYPES['NORMAL']  = $ENEMY_TYPES['SLIME'];
$ENEMY_TYPES['FAST']    = $ENEMY_TYPES['SNAKE'];
$ENEMY_TYPES['TANK']    = $ENEMY_TYPES['ARMOR'];
$ENEMY_TYPES['SWARMER'] = $ENEMY_TYPES['MUSHROOM'];
$ENEMY_TYPES['SKULL']   = $ENEMY_TYPES['SKELETON'];
$ENEMY_TYPES['SPIDER']  = $ENEMY_TYPES['SPIDER']; // preserve
$ENEMY_TYPES['SHADOW']  = $ENEMY_TYPES['GHOST'];

return true;
?>
