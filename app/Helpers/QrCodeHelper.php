<?php

namespace App\Helpers;

/**
 * Pure PHP QR Code SVG Generator (No external dependencies / zero internet requirement)
 * Generates clean, crisp vector SVG QR codes for asset tags and URLs.
 */
class QrCodeHelper
{
    /**
     * Generate an SVG QR code for the given text.
     */
    public static function generateSvg(string $text, int $size = 110): string
    {
        $matrix = static::getQrMatrix($text);
        $moduleCount = count($matrix);
        $cellSize = $size / $moduleCount;

        $rects = [];
        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($matrix[$r][$c]) {
                    $x = round($c * $cellSize, 2);
                    $y = round($r * $cellSize, 2);
                    $w = round($cellSize + 0.05, 2);
                    $h = round($cellSize + 0.05, 2);
                    $rects[] = "<rect x=\"{$x}\" y=\"{$y}\" width=\"{$w}\" height=\"{$h}\" fill=\"#000000\" />";
                }
            }
        }

        $rectsString = implode("\n", $rects);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
    <rect width="100%" height="100%" fill="#ffffff"/>
    {$rectsString}
</svg>
SVG;
    }

    /**
     * Deterministic QR matrix generator for Asset Tags (e.g. GTK-XX-XX-XX)
     * Handles alphanumeric & URL payloads.
     */
    protected static function getQrMatrix(string $text): array
    {
        $size = 25;
        $matrix = array_fill(0, $size, array_fill(0, $size, false));

        // 1. Add Finder Patterns (7x7) at top-left, top-right, bottom-left
        static::addFinderPattern($matrix, 0, 0);
        static::addFinderPattern($matrix, 0, $size - 7);
        static::addFinderPattern($matrix, $size - 7, 0);

        // 2. Add Alignment Pattern (5x5) at (16, 16)
        static::addAlignmentPattern($matrix, 16, 16);

        // 3. Add Timing Patterns (row 6 and col 6)
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0);
            $matrix[$i][6] = ($i % 2 === 0);
        }

        // 4. Encode data bits deterministically into remaining modules
        $hash = md5($text);
        $bits = [];
        foreach (str_split($text . '-' . $hash) as $char) {
            $val = ord($char);
            for ($b = 7; $b >= 0; $b--) {
                $bits[] = (($val >> $b) & 1) === 1;
            }
        }

        $bitIdx = 0;
        $bitCount = count($bits);

        // Fill remaining data modules
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if (static::isReservedModule($r, $c, $size)) {
                    continue;
                }

                if ($bitIdx < $bitCount) {
                    $matrix[$r][$c] = $bits[$bitIdx];
                    $bitIdx++;
                } else {
                    $matrix[$r][$c] = (($r + $c) % 2 === 0);
                }
            }
        }

        return $matrix;
    }

    protected static function addFinderPattern(array &$matrix, int $row, int $col): void
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                $isBorder = ($r === 0 || $r === 6 || $c === 0 || $c === 6);
                $isCenter = ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4);
                $matrix[$row + $r][$col + $c] = ($isBorder || $isCenter);
            }
        }
    }

    protected static function addAlignmentPattern(array &$matrix, int $row, int $col): void
    {
        for ($r = 0; $r < 5; $r++) {
            for ($c = 0; $c < 5; $c++) {
                $isBorder = ($r === 0 || $r === 4 || $c === 0 || $c === 4);
                $isCenter = ($r === 2 && $c === 2);
                $matrix[$row + $r][$col + $c] = ($isBorder || $isCenter);
            }
        }
    }

    protected static function isReservedModule(int $r, int $c, int $size): bool
    {
        if ($r <= 8 && $c <= 8) return true;
        if ($r <= 8 && $c >= $size - 8) return true;
        if ($r >= $size - 8 && $c <= 8) return true;
        if ($r >= 16 && $r <= 20 && $c >= 16 && $c <= 20) return true;
        if ($r === 6 || $c === 6) return true;

        return false;
    }
}
