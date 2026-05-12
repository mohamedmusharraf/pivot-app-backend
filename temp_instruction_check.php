<?php
require __DIR__ . '/vendor/autoload.php';

$examples = [
    "1. A. 2. B. Adaptation note: note",
    "Ingredients: apple, banana. Method: 1. Cut. 2. Mix.",
    '"insruction": "1. Choose one. 2. Draw. Adaptation note: be safe.",',
];

foreach ($examples as $index => $example) {
    echo "---{$index}---\n";
    echo App\Support\InstructionFormatter::normalize($example) . "\n\n";
}
