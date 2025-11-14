<?php
include 'bosses.php';

$json = json_encode($BOSSES, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Ver las primeras líneas del JSON
echo "JSON Output (primeras 500 chars):\n";
echo substr($json, 0, 500) . "\n\n";

// Ver específicamente las claves
echo "Comprobación de acceso:\n";
echo "PHP \$BOSSES[1]: " . (isset($BOSSES[1]) ? "EXISTS" : "NO EXISTE") . "\n";
echo "PHP \$BOSSES['1']: " . (isset($BOSSES['1']) ? "EXISTS" : "NO EXISTE") . "\n";

// Simular lo que hace JavaScript
$decoded = json_decode($json, true);
echo "\nDespués de json_decode:\n";
echo "Decoded[1]: " . (isset($decoded[1]) ? "EXISTS" : "NO EXISTE") . "\n";
echo "Decoded['1']: " . (isset($decoded['1']) ? "EXISTS" : "NO EXISTE") . "\n";
echo "\nKeys: " . json_encode(array_keys($decoded)) . "\n";
?>
