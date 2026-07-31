<?php
require __DIR__.'/vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$options = new QROptions([
    'outputInterface' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
    'eccLevel'        => \chillerlan\QRCode\Common\EccLevel::L,
    'addQuietzone'    => true,
    'quietzoneSize'   => 1,
    'svgAddXmlHeader' => false,
]);

$qrcode = new QRCode($options);
$svg = $qrcode->render("TEST");
echo substr($svg, 0, 100);
