<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    if (class_exists('Intervention\Image\ImageManager')) {
        // V2
        $manager = new Intervention\Image\ImageManager();
        if (method_exists($manager, 'make')) {
            $image = $manager->make('public/images/image.png');
            $image->encode('webp', 85)->save('public/images/poster.webp');
            echo "Converted using V2\n";
        } elseif (method_exists($manager, 'read')) {
            $image = $manager->read('public/images/image.png');
            $image->toWebp(85)->save('public/images/poster.webp');
            echo "Converted using V3 (read)\n";
        }
    } else {
        echo "Intervention Image not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
