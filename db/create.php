<?php

echo "Create migration file\n\n";

if ($argc < 2) {
    echo "Type migration name: ";
    $name = trim(fgets(STDIN));
} else {
    $name = $argv[1];
}
$nameEscaped = preg_replace("/\\s/", "_", strtolower($name));
$created_uts = time();

$filename = "m" . gmdate("Ymd_His", $created_uts) . "_{$nameEscaped}.sql";

echo $filename;

if (! $fp = fopen(__DIR__ . "/migrations/{$filename}", "w") ) {
    die("Failed to create migration file named {$filename}\n");
}

fputs($fp, "--\n-- Migration file: \"{$name}\" created at " . date("d.m.Y H:i:s (P)", $created_uts) . "\n--\n\n");

fclose($fp);
