#!/usr/bin/php
<?php

class YpsilonPicker
{
    public function run()
    {
        echo "Click anywhere on the screen to pick a color...\n";
        usleep(500000); // 0.5-second delay

        if (!$this->isGdbusAvailable()) {
            echo "Error: gdbus not found. Ensure you are running GNOME.\n";
            exit(1);
        }

        $colors = $this->pickColor();
        if (!$colors) {
            echo "Error: Could not retrieve color data.\n";
            exit(1);
        }

        $rgb = $this->convertToRGB($colors);
        if (count($rgb) < 3) {
            echo "Error: Invalid color data.\n";
            exit(1);
        }

        $hex = $this->convertToHex($rgb);
        $this->outputResult($rgb, $hex);
        $this->copyToClipboard($hex);
    }

    private function isGdbusAvailable(): bool
    {
        return (bool) shell_exec("command -v gdbus");
    }

    private function pickColor(): array
    {
        $command = "gdbus call --session --dest org.gnome.Shell.Screenshot --object-path /org/gnome/Shell/Screenshot --method org.gnome.Shell.Screenshot.PickColor";
        $output = shell_exec($command);

        preg_match_all('/[0-9\.]+/', $output, $matches);
        return array_map('floatval', $matches[0]);
    }

    private function convertToRGB(array $colors): array
    {
        return array_map(fn($c) => round($c * 255), $colors);
    }

    private function convertToHex(array $rgb): string
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }

    private function outputResult(array $rgb, string $hex): void
    {
        echo "RGB: {$rgb[0]} {$rgb[1]} {$rgb[2]}\n";
        echo "HEX: $hex\n";
    }

    private function copyToClipboard(string $hex): void
    {
        if (shell_exec("command -v wl-copy")) {
            shell_exec("echo -n '$hex' | wl-copy");
            echo "HEX copied to clipboard!\n";
        } else {
            echo "Install wl-clipboard (`sudo apt install wl-clipboard`) for auto-copy.\n";
        }
    }
}

(new YpsilonPicker())->run();
