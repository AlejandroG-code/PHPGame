<?php
// bosses.php - Sistema de 20 bosses épicos progresivos
// 4 Etapas: Territorial, Bestia, Planetaria, Cósmica

$BOSSES = [
    // === ETAPA I: AMENAZA TERRITORIAL (Bosses 1-5) ===
    
    1 => [
        'name' => 'The Slime God',
        'title' => 'Señor de los Lodos',
        'color' => '#6ab04c',
        'size' => 70,
        'hp' => 35,
        'shootInterval' => 1.2,
        'speed' => 45,
        'tier' => 1,
        'attacks' => ['slime_god_split', 'slime_god_rain', 'slime_god_bounce'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#6ab04c', 'pattern' => 'slime_god_split'],
            ['hp' => 0.5, 'color' => '#4a9d38', 'pattern' => 'slime_god_rain']
        ],
        'mechanics' => ['split_on_hit', 'toxic_trail']
    ],
    
    2 => [
        'name' => 'The Scaled Wrath',
        'title' => 'Furia Escamosa',
        'color' => '#2ecc71',
        'size' => 75,
        'hp' => 40,
        'shootInterval' => 1.0,
        'speed' => 55,
        'tier' => 1,
        'attacks' => ['scaled_venom_burst', 'scaled_coil_strike', 'scaled_tail_sweep'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#2ecc71', 'pattern' => 'scaled_venom_burst'],
            ['hp' => 0.6, 'color' => '#27ae60', 'pattern' => 'scaled_coil_strike'],
            ['hp' => 0.3, 'color' => '#1e8449', 'pattern' => 'scaled_tail_sweep']
        ],
        'mechanics' => ['poison_aura', 'speed_burst']
    ],
    
    3 => [
        'name' => 'The Abyss Weaver',
        'title' => 'Tejedor del Abismo',
        'color' => '#34495e',
        'size' => 72,
        'hp' => 45,
        'shootInterval' => 0.9,
        'speed' => 40,
        'tier' => 1,
        'attacks' => ['abyss_web_trap', 'abyss_shadow_bolt', 'abyss_cocoon'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#34495e', 'pattern' => 'abyss_web_trap'],
            ['hp' => 0.7, 'color' => '#2c3e50', 'pattern' => 'abyss_shadow_bolt'],
            ['hp' => 0.35, 'color' => '#1a252f', 'pattern' => 'abyss_cocoon']
        ],
        'mechanics' => ['web_field', 'teleport_dash']
    ],
    
    4 => [
        'name' => 'The Runic Colossus',
        'title' => 'Coloso Rúnico',
        'color' => '#7f8c8d',
        'size' => 85,
        'hp' => 55,
        'shootInterval' => 1.3,
        'speed' => 35,
        'tier' => 1,
        'attacks' => ['runic_hammer_slam', 'runic_shield_bash', 'runic_explosion'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#7f8c8d', 'pattern' => 'runic_hammer_slam'],
            ['hp' => 0.65, 'color' => '#95a5a6', 'pattern' => 'runic_shield_bash'],
            ['hp' => 0.3, 'color' => '#bdc3c7', 'pattern' => 'runic_explosion']
        ],
        'mechanics' => ['armor_shield', 'shockwave']
    ],
    
    5 => [
        'name' => 'Volcanic Behemoth',
        'title' => 'Behemoth Volcánico',
        'color' => '#e74c3c',
        'size' => 80,
        'hp' => 60,
        'shootInterval' => 1.1,
        'speed' => 42,
        'tier' => 1,
        'attacks' => ['volcanic_eruption', 'volcanic_meteor', 'volcanic_lava_pool'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#e74c3c', 'pattern' => 'volcanic_eruption'],
            ['hp' => 0.6, 'color' => '#c0392b', 'pattern' => 'volcanic_meteor'],
            ['hp' => 0.25, 'color' => '#a93226', 'pattern' => 'volcanic_lava_pool']
        ],
        'mechanics' => ['fire_aura', 'lava_zones']
    ],
    
    // === ETAPA II: AMENAZA BESTIA (Bosses 6-10) ===
    
    6 => [
        'name' => 'The Griffin',
        'title' => 'El Grifo Supremo',
        'color' => '#f39c12',
        'size' => 78,
        'hp' => 70,
        'shootInterval' => 0.8,
        'speed' => 65,
        'tier' => 2,
        'attacks' => ['griffin_dive_strike', 'griffin_feather_storm', 'griffin_aerial_sweep'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#f39c12', 'pattern' => 'griffin_dive_strike'],
            ['hp' => 0.7, 'color' => '#e67e22', 'pattern' => 'griffin_feather_storm'],
            ['hp' => 0.4, 'color' => '#d35400', 'pattern' => 'griffin_aerial_sweep']
        ],
        'mechanics' => ['flight_mode', 'wind_current']
    ],
    
    7 => [
        'name' => 'The Pyre King',
        'title' => 'Rey de la Pira',
        'color' => '#e84393',
        'size' => 82,
        'hp' => 75,
        'shootInterval' => 0.9,
        'speed' => 48,
        'tier' => 2,
        'attacks' => ['pyre_inferno_breath', 'pyre_flame_spiral', 'pyre_fire_rain'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#e84393', 'pattern' => 'pyre_inferno_breath'],
            ['hp' => 0.65, 'color' => '#d63031', 'pattern' => 'pyre_flame_spiral'],
            ['hp' => 0.3, 'color' => '#c0392b', 'pattern' => 'pyre_fire_rain']
        ],
        'mechanics' => ['burn_intensify', 'flame_walls']
    ],
    
    8 => [
        'name' => 'Elemental Trio',
        'title' => 'Trío Elemental',
        'color' => '#3498db',
        'size' => 65,
        'hp' => 80,
        'shootInterval' => 0.7,
        'speed' => 50,
        'tier' => 2,
        'attacks' => ['trio_ice_fire_thunder', 'trio_elemental_chaos', 'trio_fusion_blast'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#3498db', 'pattern' => 'trio_ice_fire_thunder'],
            ['hp' => 0.66, 'color' => '#9b59b6', 'pattern' => 'trio_elemental_chaos'],
            ['hp' => 0.33, 'color' => '#e74c3c', 'pattern' => 'trio_fusion_blast']
        ],
        'mechanics' => ['element_switch', 'multi_form']
    ],
    
    9 => [
        'name' => 'The Rune Smith',
        'title' => 'Herrero de Runas',
        'color' => '#8e44ad',
        'size' => 76,
        'hp' => 85,
        'shootInterval' => 1.0,
        'speed' => 42,
        'tier' => 2,
        'attacks' => ['rune_forge_hammer', 'rune_enchant_barrage', 'rune_seal_explosion'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#8e44ad', 'pattern' => 'rune_forge_hammer'],
            ['hp' => 0.6, 'color' => '#9b59b6', 'pattern' => 'rune_enchant_barrage'],
            ['hp' => 0.25, 'color' => '#be78d1', 'pattern' => 'rune_seal_explosion']
        ],
        'mechanics' => ['summon_minions', 'enchant_bullets']
    ],
    
    10 => [
        'name' => 'Elder Vampire Lord',
        'title' => 'Señor Vampiro Ancestral',
        'color' => '#c0392b',
        'size' => 74,
        'hp' => 90,
        'shootInterval' => 0.8,
        'speed' => 55,
        'tier' => 2,
        'attacks' => ['vampire_blood_drain', 'vampire_bat_swarm', 'vampire_dark_ritual'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#c0392b', 'pattern' => 'vampire_blood_drain'],
            ['hp' => 0.7, 'color' => '#922b21', 'pattern' => 'vampire_bat_swarm'],
            ['hp' => 0.35, 'color' => '#641e16', 'pattern' => 'vampire_dark_ritual']
        ],
        'mechanics' => ['life_steal', 'mist_form']
    ],
    
    // === ETAPA III: AMENAZA PLANETARIA (Bosses 11-15) ===
    
    11 => [
        'name' => 'King Midas\'s Wrath',
        'title' => 'Ira del Rey Midas',
        'color' => '#f1c40f',
        'size' => 88,
        'hp' => 110,
        'shootInterval' => 0.75,
        'speed' => 48,
        'tier' => 3,
        'attacks' => ['midas_golden_touch', 'midas_treasure_burst', 'midas_coin_rain'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#f1c40f', 'pattern' => 'midas_golden_touch'],
            ['hp' => 0.66, 'color' => '#f39c12', 'pattern' => 'midas_treasure_burst'],
            ['hp' => 0.33, 'color' => '#d68910', 'pattern' => 'midas_coin_rain']
        ],
        'mechanics' => ['petrify', 'gold_shield']
    ],
    
    12 => [
        'name' => 'Master of Illusions',
        'title' => 'Maestro de las Ilusiones',
        'color' => '#9b59b6',
        'size' => 72,
        'hp' => 100,
        'shootInterval' => 0.6,
        'speed' => 60,
        'tier' => 3,
        'attacks' => ['illusion_clone_assault', 'illusion_mirror_shatter', 'illusion_void_prison'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#9b59b6', 'pattern' => 'illusion_clone_assault'],
            ['hp' => 0.65, 'color' => '#8e44ad', 'pattern' => 'illusion_mirror_shatter'],
            ['hp' => 0.3, 'color' => '#6c3483', 'pattern' => 'illusion_void_prison']
        ],
        'mechanics' => ['clone_spawn', 'teleport_spam']
    ],
    
    13 => [
        'name' => 'The Chronos Engine',
        'title' => 'Motor de Chronos',
        'color' => '#16a085',
        'size' => 90,
        'hp' => 120,
        'shootInterval' => 0.7,
        'speed' => 40,
        'tier' => 3,
        'attacks' => ['chronos_time_stop', 'chronos_temporal_rift', 'chronos_age_decay'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#16a085', 'pattern' => 'chronos_time_stop'],
            ['hp' => 0.6, 'color' => '#1abc9c', 'pattern' => 'chronos_temporal_rift'],
            ['hp' => 0.25, 'color' => '#0e6655', 'pattern' => 'chronos_age_decay']
        ],
        'mechanics' => ['slow_field', 'bullet_rewind']
    ],
    
    14 => [
        'name' => 'The Solar Phoenix',
        'title' => 'Fénix Solar',
        'color' => '#ff6348',
        'size' => 85,
        'hp' => 115,
        'shootInterval' => 0.65,
        'speed' => 62,
        'tier' => 3,
        'attacks' => ['phoenix_solar_flare', 'phoenix_rebirth_nova', 'phoenix_plasma_wings'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#ff6348', 'pattern' => 'phoenix_solar_flare'],
            ['hp' => 0.7, 'color' => '#ff793f', 'pattern' => 'phoenix_rebirth_nova'],
            ['hp' => 0.4, 'color' => '#ffa502', 'pattern' => 'phoenix_plasma_wings'],
            ['hp' => 0.1, 'color' => '#ffd32a', 'pattern' => 'phoenix_rebirth_nova', 'resurrect' => true]
        ],
        'mechanics' => ['resurrect_once', 'fire_trail']
    ],
    
    15 => [
        'name' => 'The Keeper of the Nexus',
        'title' => 'Guardián del Nexo',
        'color' => '#2c3e50',
        'size' => 95,
        'hp' => 130,
        'shootInterval' => 0.8,
        'speed' => 38,
        'tier' => 3,
        'attacks' => ['nexus_dimension_tear', 'nexus_void_lance', 'nexus_reality_collapse'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#2c3e50', 'pattern' => 'nexus_dimension_tear'],
            ['hp' => 0.65, 'color' => '#34495e', 'pattern' => 'nexus_void_lance'],
            ['hp' => 0.3, 'color' => '#1c2833', 'pattern' => 'nexus_reality_collapse']
        ],
        'mechanics' => ['portal_spawn', 'gravity_well']
    ],
    
    // === ETAPA IV: AMENAZA CÓSMICA (Bosses 16-20) ===
    
    16 => [
        'name' => 'The Void Weaver',
        'title' => 'Tejedor del Vacío',
        'color' => '#130f40',
        'size' => 92,
        'hp' => 150,
        'shootInterval' => 0.55,
        'speed' => 52,
        'tier' => 4,
        'attacks' => ['void_entropy_wave', 'void_singularity', 'void_unraveling'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#130f40', 'pattern' => 'void_entropy_wave'],
            ['hp' => 0.7, 'color' => '#1e1b4b', 'pattern' => 'void_singularity'],
            ['hp' => 0.4, 'color' => '#0c0a27', 'pattern' => 'void_unraveling']
        ],
        'mechanics' => ['void_zones', 'existence_drain']
    ],
    
    17 => [
        'name' => 'The Primordial Wyrm',
        'title' => 'Gusano Primordial',
        'color' => '#006266',
        'size' => 100,
        'hp' => 160,
        'shootInterval' => 0.6,
        'speed' => 45,
        'tier' => 4,
        'attacks' => ['wyrm_cosmic_breath', 'wyrm_meteor_shower', 'wyrm_planet_crush'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#006266', 'pattern' => 'wyrm_cosmic_breath'],
            ['hp' => 0.66, 'color' => '#079992', 'pattern' => 'wyrm_meteor_shower'],
            ['hp' => 0.33, 'color' => '#0c4f60', 'pattern' => 'wyrm_planet_crush']
        ],
        'mechanics' => ['segment_spawn', 'burrow_strike']
    ],
    
    18 => [
        'name' => 'The Soul of the World',
        'title' => 'Alma del Mundo',
        'color' => '#22a6b3',
        'size' => 98,
        'hp' => 175,
        'shootInterval' => 0.5,
        'speed' => 50,
        'tier' => 4,
        'attacks' => ['soul_essence_drain', 'soul_spectral_army', 'soul_life_transfer'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#22a6b3', 'pattern' => 'soul_essence_drain'],
            ['hp' => 0.7, 'color' => '#3498db', 'pattern' => 'soul_spectral_army'],
            ['hp' => 0.35, 'color' => '#5f27cd', 'pattern' => 'soul_life_transfer']
        ],
        'mechanics' => ['soul_harvest', 'ghost_phase']
    ],
    
    19 => [
        'name' => 'The Unbound One',
        'title' => 'El Desatado',
        'color' => '#ff4757',
        'size' => 105,
        'hp' => 200,
        'shootInterval' => 0.45,
        'speed' => 58,
        'tier' => 4,
        'attacks' => ['unbound_chaos_storm', 'unbound_reality_break', 'unbound_omnidirectional'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#ff4757', 'pattern' => 'unbound_chaos_storm'],
            ['hp' => 0.75, 'color' => '#ff6348', 'pattern' => 'unbound_reality_break'],
            ['hp' => 0.5, 'color' => '#ff793f', 'pattern' => 'unbound_omnidirectional'],
            ['hp' => 0.25, 'color' => '#ffa502', 'pattern' => 'unbound_chaos_storm']
        ],
        'mechanics' => ['chain_phases', 'invuln_burst']
    ],
    
    20 => [
        'name' => 'The Absolute Deity',
        'title' => 'Deidad Absoluta',
        'color' => '#ffffff',
        'size' => 110,
        'hp' => 250,
        'shootInterval' => 0.4,
        'speed' => 48,
        'tier' => 4,
        'attacks' => ['deity_divine_judgment', 'deity_creation_destruction', 'deity_infinite_cycle', 'deity_apocalypse'],
        'phases' => [
            ['hp' => 1.0, 'color' => '#ffffff', 'pattern' => 'deity_divine_judgment'],
            ['hp' => 0.75, 'color' => '#f8f9fa', 'pattern' => 'deity_creation_destruction'],
            ['hp' => 0.5, 'color' => '#e9ecef', 'pattern' => 'deity_infinite_cycle'],
            ['hp' => 0.25, 'color' => '#dee2e6', 'pattern' => 'deity_apocalypse'],
            ['hp' => 0.1, 'color' => '#000000', 'pattern' => 'deity_apocalypse', 'enrage' => true]
        ],
        'mechanics' => ['omni_form', 'bullet_hell', 'screen_distortion']
    ]
];

// Devolver true para compatibilidad con includes
return true;
?>
