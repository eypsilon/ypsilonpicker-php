#!/usr/bin/php
<?php

echo "Click anywhere on the screen to pick a color...\n";
usleep(500000); // 0.5-second delay

// Ensure gdbus is available
if (!shell_exec("command -v gdbus")) {
    echo "Error: gdbus not found. Ensure you are running GNOME.\n";
    exit(1);
}

// Execute the gdbus command
$command = "gdbus call --session --dest org.gnome.Shell.Screenshot --object-path /org/gnome/Shell/Screenshot --method org.gnome.Shell.Screenshot.PickColor";
$output = shell_exec($command);

if (!$output) {
    echo "Error: Could not retrieve color data.\n";
    exit(1);
}

// Extract numeric values from the output
preg_match_all('/[0-9\.]+/', $output, $matches);
$colors = array_map('floatval', $matches[0]);

// Convert to 255-based RGB format
$rgb = array_map(fn($c) => round($c * 255), $colors);

if (count($rgb) < 3) {
    echo "Error: Invalid color data.\n";
    exit(1);
}

// Convert to HEX format
$hex = sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);

// Output the results
echo "RGB: {$rgb[0]} {$rgb[1]} {$rgb[2]}\n";
echo "HEX: $hex\n";

// Copy to clipboard, if wl-clipboard is installed
if (shell_exec("command -v wl-copy")) {
    shell_exec("echo -n '$hex' | wl-copy");
    echo "HEX copied to clipboard!\n";
} else {
    echo "Install wl-clipboard (`sudo apt install wl-clipboard`) for auto-copy.\n";
}
