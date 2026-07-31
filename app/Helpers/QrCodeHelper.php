<?php

namespace App\Helpers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * Generates clean, crisp vector SVG QR codes for asset tags and URLs.
 */
class QrCodeHelper
{
    /**
     * Generate an SVG QR code for the given text.
     */
    public static function generateSvg(string $text, int $size = 110): string
    {
        $options = new QROptions([
            'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
            'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::L, // Low error correction for less dense QR
            'addQuietzone'    => true,
            'quietzoneSize'   => 1,
            'svgAddXmlHeader' => false,
        ]);

        $qrcode = new QRCode($options);
        $svg = $qrcode->render($text);

        // Strip the XML declaration if present so it can be safely embedded in HTML
        if (str_starts_with(trim($svg), '<?xml')) {
            $svg = preg_replace('/<\?xml.*?\?>/is', '', $svg);
        }

        return trim($svg);
    }
}
