// client_data.php - emits game data (ENEMY_TYPES, GUNS, ATTACKS, WORLD, BOSSES, BOSS_ATTACKS) as JS constants
// This file should be included INSIDE a <script> block in game.php
const ENEMY_TYPES = <?php echo json_encode($ENEMY_TYPES, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const GUNS = <?php echo json_encode($GUNS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const ATTACKS = <?php echo json_encode($ATTACKS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const WORLD = <?php echo json_encode($WORLD ?? ['BORDER'=>50,'BOSS_INTERVAL'=>5], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const SKILLS = <?php echo json_encode($SKILLS ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const BOSSES = <?php echo json_encode($BOSSES ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
const BOSS_ATTACKS = <?php echo json_encode(array_merge($ATTACKS ?? [], $BOSS_ATTACKS ?? []), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
