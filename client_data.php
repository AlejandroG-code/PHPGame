// client_data.php - emits game data (ENEMY_TYPES, GUNS, ATTACKS, WORLD) as JS constants
// This file should be included INSIDE a <script> block in game.php
const ENEMY_TYPES = <?php echo json_encode($ENEMY_TYPES, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const GUNS = <?php echo json_encode($GUNS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const ATTACKS = <?php echo json_encode($ATTACKS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const WORLD = <?php echo json_encode($WORLD ?? ['BORDER'=>50,'BOSS_INTERVAL'=>5], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const SKILLS = <?php echo json_encode($SKILLS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
// BOSSES (optional) - viene de bosses.php si existe
<?php if (file_exists(__DIR__ . '/bosses.php')) {
	include __DIR__ . '/bosses.php';
	echo 'const BOSSES = ' . json_encode($BOSSES ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ";\n";
} else {
	echo 'const BOSSES = {};\n';
}
