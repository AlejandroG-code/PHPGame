<?php
// boss_attacks.php - Patrones de ataque únicos para los 20 bosses

$BOSS_ATTACKS = [
    // === ETAPA I: TERRITORIAL ===
    
    // Boss 1: The Slime God
    'slime_god_split' => ['name' => 'slime_god_split', 'speed' => 140, 'count' => 16, 'pattern' => 'circle', 'color' => '#6ab04c', 'shape' => 'orb', 'split_on_hit' => true],
    'slime_god_rain' => ['name' => 'slime_god_rain', 'speed' => 100, 'count' => 24, 'pattern' => 'rain', 'color' => '#4a9d38', 'shape' => 'drop_light', 'gravity' => 200],
    'slime_god_bounce' => ['name' => 'slime_god_bounce', 'speed' => 180, 'count' => 8, 'pattern' => 'aimed', 'color' => '#6ab04c', 'shape' => 'orb', 'bounce' => 3],
    
    // Boss 2: The Scaled Wrath
    'scaled_venom_burst' => ['name' => 'scaled_venom_burst', 'speed' => 160, 'count' => 20, 'pattern' => 'burst', 'color' => '#2ecc71', 'shape' => 'toxic', 'poison' => 2],
    'scaled_coil_strike' => ['name' => 'scaled_coil_strike', 'speed' => 220, 'count' => 6, 'pattern' => 'spiral', 'color' => '#27ae60', 'shape' => 'needle', 'tracking' => 0.03],
    'scaled_tail_sweep' => ['name' => 'scaled_tail_sweep', 'speed' => 200, 'count' => 30, 'pattern' => 'cone', 'color' => '#1e8449', 'shape' => 'slash', 'knockback' => 300],
    
    // Boss 3: The Abyss Weaver
    'abyss_web_trap' => ['name' => 'abyss_web_trap', 'speed' => 120, 'count' => 12, 'pattern' => 'web', 'color' => '#34495e', 'shape' => 'web_proj', 'slow' => 0.7, 'duration' => 3],
    'abyss_shadow_bolt' => ['name' => 'abyss_shadow_bolt', 'speed' => 240, 'count' => 8, 'pattern' => 'aimed', 'color' => '#2c3e50', 'shape' => 'wisp', 'phase_through' => true],
    'abyss_cocoon' => ['name' => 'abyss_cocoon', 'speed' => 0, 'count' => 4, 'pattern' => 'trap_zone', 'color' => '#1a252f', 'shape' => 'zone_trap', 'immobilize' => 2],
    
    // Boss 4: The Runic Colossus
    'runic_hammer_slam' => ['name' => 'runic_hammer_slam', 'speed' => 150, 'count' => 24, 'pattern' => 'shockwave', 'color' => '#7f8c8d', 'shape' => 'chunk', 'damage' => 2],
    'runic_shield_bash' => ['name' => 'runic_shield_bash', 'speed' => 180, 'count' => 12, 'pattern' => 'line', 'color' => '#95a5a6', 'shape' => 'chunk', 'pierce' => true],
    'runic_explosion' => ['name' => 'runic_explosion', 'speed' => 200, 'count' => 36, 'pattern' => 'circle', 'color' => '#bdc3c7', 'shape' => 'spark', 'explosion_radius' => 40],
    
    // Boss 5: Volcanic Behemoth
    'volcanic_eruption' => ['name' => 'volcanic_eruption', 'speed' => 180, 'count' => 20, 'pattern' => 'volcano', 'color' => '#e74c3c', 'shape' => 'ball_fire', 'fire_trail' => true],
    'volcanic_meteor' => ['name' => 'volcanic_meteor', 'speed' => 140, 'count' => 8, 'pattern' => 'meteor', 'color' => '#c0392b', 'shape' => 'lob', 'explosion' => 50],
    'volcanic_lava_pool' => ['name' => 'volcanic_lava_pool', 'speed' => 0, 'count' => 6, 'pattern' => 'zone', 'color' => '#a93226', 'shape' => 'zone_fire', 'dot_damage' => 1, 'duration' => 8],
    
    // === ETAPA II: BESTIA ===
    
    // Boss 6: The Griffin
    'griffin_dive_strike' => ['name' => 'griffin_dive_strike', 'speed' => 280, 'count' => 4, 'pattern' => 'dive', 'color' => '#f39c12', 'shape' => 'slash', 'telegraph' => 0.6],
    'griffin_feather_storm' => ['name' => 'griffin_feather_storm', 'speed' => 190, 'count' => 40, 'pattern' => 'spiral', 'color' => '#e67e22', 'shape' => 'needle', 'wind_push' => 150],
    'griffin_aerial_sweep' => ['name' => 'griffin_aerial_sweep', 'speed' => 220, 'count' => 16, 'pattern' => 'arc', 'color' => '#d35400', 'shape' => 'slash', 'wide' => true],
    
    // Boss 7: The Pyre King
    'pyre_inferno_breath' => ['name' => 'pyre_inferno_breath', 'speed' => 200, 'count' => 30, 'pattern' => 'cone_wide', 'color' => '#e84393', 'shape' => 'cone_flame', 'burn' => 4],
    'pyre_flame_spiral' => ['name' => 'pyre_flame_spiral', 'speed' => 170, 'count' => 24, 'pattern' => 'double_spiral', 'color' => '#d63031', 'shape' => 'flame', 'heat_wave' => true],
    'pyre_fire_rain' => ['name' => 'pyre_fire_rain', 'speed' => 120, 'count' => 50, 'pattern' => 'rain_dense', 'color' => '#c0392b', 'shape' => 'drop_light', 'fire_pool' => true],
    
    // Boss 8: Elemental Trio
    'trio_ice_fire_thunder' => ['name' => 'trio_ice_fire_thunder', 'speed' => 180, 'count' => 18, 'pattern' => 'tri_element', 'color' => '#3498db', 'shape' => 'orb_glow', 'multi_effect' => true],
    'trio_elemental_chaos' => ['name' => 'trio_elemental_chaos', 'speed' => 200, 'count' => 36, 'pattern' => 'random_chaos', 'color' => '#9b59b6', 'shape' => 'spark', 'element_cycle' => true],
    'trio_fusion_blast' => ['name' => 'trio_fusion_blast', 'speed' => 220, 'count' => 12, 'pattern' => 'triangle', 'color' => '#e74c3c', 'shape' => 'orb_tail', 'fusion_explosion' => 60],
    
    // Boss 9: The Rune Smith
    'rune_forge_hammer' => ['name' => 'rune_forge_hammer', 'speed' => 160, 'count' => 16, 'pattern' => 'grid', 'color' => '#8e44ad', 'shape' => 'rune', 'enchant' => 'slow'],
    'rune_enchant_barrage' => ['name' => 'rune_enchant_barrage', 'speed' => 200, 'count' => 30, 'pattern' => 'barrage', 'color' => '#9b59b6', 'shape' => 'diamond', 'random_enchant' => true],
    'rune_seal_explosion' => ['name' => 'rune_seal_explosion', 'speed' => 180, 'count' => 48, 'pattern' => 'seal', 'color' => '#be78d1', 'shape' => 'rune', 'seal_burst' => 70],
    
    // Boss 10: Elder Vampire Lord
    'vampire_blood_drain' => ['name' => 'vampire_blood_drain', 'speed' => 150, 'count' => 12, 'pattern' => 'homing_strong', 'color' => '#c0392b', 'shape' => 'toxic_drop', 'life_steal' => 2],
    'vampire_bat_swarm' => ['name' => 'vampire_bat_swarm', 'speed' => 180, 'count' => 40, 'pattern' => 'swarm', 'color' => '#922b21', 'shape' => 'wisp', 'blind' => 1],
    'vampire_dark_ritual' => ['name' => 'vampire_dark_ritual', 'speed' => 140, 'count' => 24, 'pattern' => 'ritual_circle', 'color' => '#641e16', 'shape' => 'orb_dark', 'summon_minion' => true],
    
    // === ETAPA III: PLANETARIA ===
    
    // Boss 11: King Midas's Wrath
    'midas_golden_touch' => ['name' => 'midas_golden_touch', 'speed' => 160, 'count' => 20, 'pattern' => 'aimed_spread', 'color' => '#f1c40f', 'shape' => 'coin', 'petrify' => 2],
    'midas_treasure_burst' => ['name' => 'midas_treasure_burst', 'speed' => 190, 'count' => 32, 'pattern' => 'burst_multi', 'color' => '#f39c12', 'shape' => 'diamond', 'gold_shield' => true],
    'midas_coin_rain' => ['name' => 'midas_coin_rain', 'speed' => 130, 'count' => 60, 'pattern' => 'rain_heavy', 'color' => '#d68910', 'shape' => 'coin', 'stun' => 0.5],
    
    // Boss 12: Master of Illusions
    'illusion_clone_assault' => ['name' => 'illusion_clone_assault', 'speed' => 200, 'count' => 24, 'pattern' => 'clone_fire', 'color' => '#9b59b6', 'shape' => 'wisp', 'fake_bullets' => 0.4],
    'illusion_mirror_shatter' => ['name' => 'illusion_mirror_shatter', 'speed' => 220, 'count' => 40, 'pattern' => 'shatter', 'color' => '#8e44ad', 'shape' => 'shard', 'confusion' => true],
    'illusion_void_prison' => ['name' => 'illusion_void_prison', 'speed' => 0, 'count' => 8, 'pattern' => 'prison', 'color' => '#6c3483', 'shape' => 'zone_trap', 'cage' => 3],
    
    // Boss 13: The Chronos Engine
    'chronos_time_stop' => ['name' => 'chronos_time_stop', 'speed' => 170, 'count' => 16, 'pattern' => 'slow_spiral', 'color' => '#16a085', 'shape' => 'orb_glow', 'time_slow' => 0.5, 'duration' => 4],
    'chronos_temporal_rift' => ['name' => 'chronos_temporal_rift', 'speed' => 200, 'count' => 28, 'pattern' => 'rift', 'color' => '#1abc9c', 'shape' => 'beam_short', 'bullet_rewind' => true],
    'chronos_age_decay' => ['name' => 'chronos_age_decay', 'speed' => 140, 'count' => 20, 'pattern' => 'decay_wave', 'color' => '#0e6655', 'shape' => 'wisp', 'age_damage' => 3],
    
    // Boss 14: The Solar Phoenix
    'phoenix_solar_flare' => ['name' => 'phoenix_solar_flare', 'speed' => 210, 'count' => 36, 'pattern' => 'sun_burst', 'color' => '#ff6348', 'shape' => 'ball_fire', 'screen_flash' => true],
    'phoenix_rebirth_nova' => ['name' => 'phoenix_rebirth_nova', 'speed' => 180, 'count' => 48, 'pattern' => 'nova', 'color' => '#ff793f', 'shape' => 'cone_flame', 'knockback' => 400],
    'phoenix_plasma_wings' => ['name' => 'phoenix_plasma_wings', 'speed' => 220, 'count' => 40, 'pattern' => 'wings', 'color' => '#ffa502', 'shape' => 'slash', 'plasma_trail' => true],
    
    // Boss 15: The Keeper of the Nexus
    'nexus_dimension_tear' => ['name' => 'nexus_dimension_tear', 'speed' => 190, 'count' => 24, 'pattern' => 'tear', 'color' => '#2c3e50', 'shape' => 'beam_short', 'portal_spawn' => true],
    'nexus_void_lance' => ['name' => 'nexus_void_lance', 'speed' => 260, 'count' => 8, 'pattern' => 'lance', 'color' => '#34495e', 'shape' => 'needle', 'pierce_all' => true],
    'nexus_reality_collapse' => ['name' => 'nexus_reality_collapse', 'speed' => 160, 'count' => 60, 'pattern' => 'collapse', 'color' => '#1c2833', 'shape' => 'spark', 'gravity_pull' => 200],
    
    // === ETAPA IV: CÓSMICA ===
    
    // Boss 16: The Void Weaver
    'void_entropy_wave' => ['name' => 'void_entropy_wave', 'speed' => 180, 'count' => 32, 'pattern' => 'entropy', 'color' => '#130f40', 'shape' => 'wisp', 'existence_drain' => 2],
    'void_singularity' => ['name' => 'void_singularity', 'speed' => 140, 'count' => 16, 'pattern' => 'singularity', 'color' => '#1e1b4b', 'shape' => 'orb_dark', 'black_hole' => 250],
    'void_unraveling' => ['name' => 'void_unraveling', 'speed' => 200, 'count' => 48, 'pattern' => 'unravel', 'color' => '#0c0a27', 'shape' => 'zigzag', 'reality_tear' => true],
    
    // Boss 17: The Primordial Wyrm
    'wyrm_cosmic_breath' => ['name' => 'wyrm_cosmic_breath', 'speed' => 220, 'count' => 40, 'pattern' => 'breath_cosmic', 'color' => '#006266', 'shape' => 'cone_flame', 'cosmic_damage' => 3],
    'wyrm_meteor_shower' => ['name' => 'wyrm_meteor_shower', 'speed' => 150, 'count' => 30, 'pattern' => 'meteor_field', 'color' => '#079992', 'shape' => 'lob', 'crater' => 60],
    'wyrm_planet_crush' => ['name' => 'wyrm_planet_crush', 'speed' => 100, 'count' => 12, 'pattern' => 'crush', 'color' => '#0c4f60', 'shape' => 'chunk', 'massive_damage' => 4],
    
    // Boss 18: The Soul of the World
    'soul_essence_drain' => ['name' => 'soul_essence_drain', 'speed' => 170, 'count' => 28, 'pattern' => 'soul_drain', 'color' => '#22a6b3', 'shape' => 'orb_tail', 'soul_steal' => 3],
    'soul_spectral_army' => ['name' => 'soul_spectral_army', 'speed' => 190, 'count' => 50, 'pattern' => 'army', 'color' => '#3498db', 'shape' => 'wisp', 'summon_ghosts' => 5],
    'soul_life_transfer' => ['name' => 'soul_life_transfer', 'speed' => 160, 'count' => 20, 'pattern' => 'transfer', 'color' => '#5f27cd', 'shape' => 'orb_glow', 'life_swap' => true],
    
    // Boss 19: The Unbound One
    'unbound_chaos_storm' => ['name' => 'unbound_chaos_storm', 'speed' => 210, 'count' => 60, 'pattern' => 'chaos', 'color' => '#ff4757', 'shape' => 'spark', 'random_all' => true],
    'unbound_reality_break' => ['name' => 'unbound_reality_break', 'speed' => 230, 'count' => 36, 'pattern' => 'reality_break', 'color' => '#ff6348', 'shape' => 'shard', 'screen_break' => true],
    'unbound_omnidirectional' => ['name' => 'unbound_omnidirectional', 'speed' => 200, 'count' => 80, 'pattern' => 'omni', 'color' => '#ff793f', 'shape' => 'orb', 'all_directions' => true],
    
    // Boss 20: The Absolute Deity
    'deity_divine_judgment' => ['name' => 'deity_divine_judgment', 'speed' => 240, 'count' => 50, 'pattern' => 'judgment', 'color' => '#ffffff', 'shape' => 'beam_short', 'divine_damage' => 4],
    'deity_creation_destruction' => ['name' => 'deity_creation_destruction', 'speed' => 200, 'count' => 70, 'pattern' => 'duality', 'color' => '#f8f9fa', 'shape' => 'orb_glow', 'create_destroy' => true],
    'deity_infinite_cycle' => ['name' => 'deity_infinite_cycle', 'speed' => 180, 'count' => 90, 'pattern' => 'infinite', 'color' => '#e9ecef', 'shape' => 'spiral', 'perpetual' => true],
    'deity_apocalypse' => ['name' => 'deity_apocalypse', 'speed' => 220, 'count' => 100, 'pattern' => 'apocalypse', 'color' => '#dee2e6', 'shape' => 'spark', 'end_times' => true]
];

return true;
?>
