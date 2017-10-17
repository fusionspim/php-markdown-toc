<?php
include __DIR__ . '/../vendor/autoload.php';

define('TEST_FIXTURE_DIR', __DIR__ . '/fixtures/');
define('TEST_OUTPUT_DIR', __DIR__ . '/output/');

$paths = [
    TEST_OUTPUT_DIR,
    TEST_OUTPUT_DIR . 'markdown/',
];

foreach ($paths as $path) {
    if (! file_exists($path)) {
        mkdir($path, 0777, true);
    } else {
        array_map('unlink', glob($path . '*.*'));
    }
}