<?php
include 'bosses.php';

echo "Keys: " . json_encode(array_keys($BOSSES)) . "\n\n";
echo "First boss: " . json_encode($BOSSES[1]) . "\n\n";
echo "Second boss: " . json_encode($BOSSES[2]) . "\n";
?>
