// client_config.php - emits JS CONFIG from PHP $config
// This file is intended to be included INSIDE a <script> block in game.php
const CONFIG = {
    CANVAS_W: <?php echo $config['canvas_width']; ?>,
    CANVAS_H: <?php echo $config['canvas_height']; ?>,
    BORDER: 50,
    PLAYER_SPEED: <?php echo $config['player_speed']; ?>,
    PLAYER_SIZE: <?php echo $config['player_size']; ?>,
    MAX_HEALTH: <?php echo $config['player_health']; ?>,
    BULLET_SPEED: <?php echo $config['bullet_speed']; ?>,
    ENEMY_BULLET_SPEED: <?php echo $config['enemy_bullet_speed']; ?>,
    MAX_ROOMS: <?php echo $config['max_rooms']; ?>
};
