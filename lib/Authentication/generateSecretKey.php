<?php
use Core\Constants\Constants;

require __DIR__ . '/../../config/bootstrap.php';
$secretKeyPath = Constants::rootPath()->join('/secret_key');

try {
    $secretKey = bin2hex(random_bytes(32));

    file_put_contents($secretKeyPath, $secretKey);

    echo "Secret key generated and saved to 'secret_key'.\n";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
}

?>
