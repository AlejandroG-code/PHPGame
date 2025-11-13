<?php
// bosses.php - definiciones de bosses y metadatos para exportar a cliente
$BOSSES = [
    'flame_laron' => [
        'name' => 'Flame Laron',
        'base_hp' => 120,
        'color' => '#ff7043',
        'size' => 84,
        'attacks' => ['boss_phase1','boss_burst1','boss_enrage']
    ],
    'frostwyrm' => [
        'name' => 'Frost Wyrm',
        'base_hp' => 140,
        'color' => '#74b9ff',
        'size' => 92,
        'attacks' => ['boss_phase1','boss_phase2','boss_phase3']
    ],
    'storm_champion' => [
        'name' => 'Storm Champion',
        'base_hp' => 160,
        'color' => '#ffe066',
        'size' => 88,
        'attacks' => ['boss_phase2','boss_laser3','boss_phase4']
    ],
    'void_keeper' => [
        'name' => 'Void Keeper',
        'base_hp' => 200,
        'color' => '#9b59b6',
        'size' => 100,
        'attacks' => ['boss_phase3','boss_enrage','boss_phase4']
    ]
];

return true;
?>
