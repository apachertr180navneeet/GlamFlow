<?php
$srcPath = 'C:\\Users\\navne\\.gemini\\antigravity-ide\\brain\\b82702e6-934f-43ab-b311-65b791cc63ec\\media__1785137274324.jpg';
if (!file_exists($srcPath)) {
    die("Source file not found\n");
}

$srcImg = imagecreatefromjpeg($srcPath);
$w = imagesx($srcImg);
$h = imagesy($srcImg);

echo "Source image loaded: {$w}x{$h}\n";

// Function to create transparent logo with custom foreground color
function createLogo($srcImg, $w, $h, $r, $g, $b) {
    $outImg = imagecreatetruecolor($w, $h);
    imagealphablending($outImg, false);
    imagesavealpha($outImg, true);
    
    $transparent = imagecolorallocatealpha($outImg, 0, 0, 0, 127);
    imagefill($outImg, 0, 0, $transparent);
    
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgb = imagecolorat($srcImg, $x, $y);
            $cr = ($rgb >> 16) & 0xFF;
            $cg = ($rgb >> 8) & 0xFF;
            $cb = $rgb & 0xFF;
            
            // Calculate luminance for thresholding
            $lum = ($cr * 0.299 + $cg * 0.587 + $cb * 0.114) / 255.0;
            
            if ($lum > 0.08) {
                // High contrast alpha mapping
                $alphaLum = min(1.0, max(0.0, ($lum - 0.08) / 0.85));
                $alpha = (int)((1.0 - $alphaLum) * 127.0);
                if ($alpha < 0) $alpha = 0;
                if ($alpha > 127) $alpha = 127;
                
                $col = imagecolorallocatealpha($outImg, $r, $g, $b, $alpha);
                imagesetpixel($outImg, $x, $y, $col);
            }
        }
    }
    return $outImg;
}

// Function to create high resolution favicon with rounded coffee badge and white infinity icon
function createFavicon($srcImg, $w, $h, $size = 64) {
    $fav = imagecreatetruecolor($size, $size);
    imagealphablending($fav, true);
    imagesavealpha($fav, true);
    
    $transparent = imagecolorallocatealpha($fav, 0, 0, 0, 127);
    imagefill($fav, 0, 0, $transparent);
    
    // Coffee background #5C3A21
    $bgCol = imagecolorallocate($fav, 92, 58, 33);
    
    // Draw rounded rectangle/circle badge
    $radius = (int)($size * 0.25);
    for ($x = 0; $x < $size; $x++) {
        for ($y = 0; $y < $size; $y++) {
            // Distance from rounded corners
            $dx = max(0, max($radius - $x, $x - ($size - 1 - $radius)));
            $dy = max(0, max($radius - $y, $y - ($size - 1 - $radius)));
            if ($dx * $dx + $dy * $dy <= $radius * $radius) {
                imagesetpixel($fav, $x, $y, $bgCol);
            }
        }
    }
    
    // Scale and center srcImg onto favicon
    $margin = (int)($size * 0.12);
    $targetW = $size - ($margin * 2);
    $targetH = (int)(($h / $w) * $targetW);
    if ($targetH > $size - ($margin * 2)) {
        $targetH = $size - ($margin * 2);
        $targetW = (int)(($w / $h) * $targetH);
    }
    $offsetX = (int)(($size - $targetW) / 2);
    $offsetY = (int)(($size - $targetH) / 2);
    
    for ($x = 0; $x < $targetW; $x++) {
        for ($y = 0; $y < $targetH; $y++) {
            $srcX = (int)(($x / $targetW) * $w);
            $srcY = (int)(($y / $targetH) * $h);
            
            $rgb = imagecolorat($srcImg, $srcX, $srcY);
            $cr = ($rgb >> 16) & 0xFF;
            $cg = ($rgb >> 8) & 0xFF;
            $cb = $rgb & 0xFF;
            $lum = ($cr * 0.299 + $cg * 0.587 + $cb * 0.114) / 255.0;
            
            if ($lum > 0.12) {
                // Blend crisp white over coffee background
                $blendR = (int)(92 + (255 - 92) * $lum);
                $blendG = (int)(58 + (255 - 58) * $lum);
                $blendB = (int)(33 + (255 - 33) * $lum);
                $col = imagecolorallocate($fav, min(255, $blendR), min(255, $blendG), min(255, $blendB));
                imagesetpixel($fav, $x + $offsetX, $y + $offsetY, $col);
            }
        }
    }
    return $fav;
}

// Generate coffee logo, white logo, and mocha logo
$coffeeLogo = createLogo($srcImg, $w, $h, 92, 58, 33);     // #5C3A21
$mochaLogo  = createLogo($srcImg, $w, $h, 166, 124, 82);   // #A67C52
$whiteLogo  = createLogo($srcImg, $w, $h, 255, 255, 255); // #FFFFFF

$fav64 = createFavicon($srcImg, $w, $h, 64);
$fav32 = createFavicon($srcImg, $w, $h, 32);

// Save paths
$directories = [
    'public/assets/admin/img',
    'public/assets/admin/img/favicon',
    'public/assets/web/img',
    'public/website/images',
    'website/images'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

imagepng($coffeeLogo, 'public/assets/admin/img/logo.png');
imagepng($coffeeLogo, 'public/assets/web/img/logo.png');
imagepng($coffeeLogo, 'public/website/images/logo.png');
imagepng($coffeeLogo, 'website/images/logo.png');

imagepng($mochaLogo, 'public/assets/admin/img/logo-mocha.png');
imagepng($mochaLogo, 'public/assets/web/img/logo-mocha.png');

imagepng($whiteLogo, 'public/assets/admin/img/logo-white.png');
imagepng($whiteLogo, 'public/assets/web/img/logo-white.png');

imagepng($fav64, 'public/assets/admin/img/favicon/favicon-64x64.png');
imagepng($fav32, 'public/assets/admin/img/favicon/favicon-32x32.png');
imagepng($fav32, 'public/assets/admin/img/favicon/favicon.png');
imagepng($fav32, 'public/assets/admin/img/favicon/favicon.ico');
imagepng($fav32, 'public/favicon.ico');

echo "All logo and favicon assets successfully generated!\n";
