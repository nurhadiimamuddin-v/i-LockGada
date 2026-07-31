<?php
$sourceFile = 'images/pegadaian_keyhole_v5.png';
$outputFile = 'images/pegadaian_keyhole_v6.png';

$im = imagecreatefrompng($sourceFile);
if (!$im) die('Failed to load image');

imagealphablending($im, false);
imagesavealpha($im, true);

$width = imagesx($im);
$height = imagesy($im);

// The AI image generator uses a studio background which gets a bit grey at the edges
// Let's use a tolerance of > 230 to catch all off-whites
for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $color = imagecolorat($im, $x, $y);
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;

        if ($r > 230 && $g > 230 && $b > 230) {
            $transparent = imagecolorallocatealpha($im, 255, 255, 255, 127);
            imagesetpixel($im, $x, $y, $transparent);
        }
    }
}

imagepng($im, $outputFile);
imagedestroy($im);
echo "Background removed successfully.";
?>
