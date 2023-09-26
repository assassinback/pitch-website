<?php // content="text/plain; charset=utf-8"
require_once ('config.php');

$image = isset($_GET['image']) ? $_GET['image'] : '';

if (file_exists(USER_PATH . $image)) {
    $filename = USER_PATH . $image;
} else {
    exit;
}
//$filename = 'icon1.jpg';

$maxWidth = 297;
$maxHeight = 376;
$maxRatio = ($maxWidth/$maxHeight);

$im = imagecreatefromjpeg('images/imgbg.jpg');
$type = mime_content_type($filename);

// Get new sizes
list($width, $height) = getimagesize($filename);
$ratio = ($width/$height);

if ($ratio > $maxRatio) {
    if ($width > $maxWidth) {
        $newWidth = $maxWidth;
    } else {
        $newWidth = $width;
    }
    $newHeight = $newWidth / $ratio;
} else {
    if ($height > $maxHeight) {
        $newHeight = $maxHeight;
    } else {
        $newHeight = $height;
    }
    $newWidth = $newHeight * $ratio;
}

// Load
$stamp = imagecreatetruecolor($newWidth, $newHeight);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($stamp, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

$sx = imagesx($stamp);
$sy = imagesy($stamp);

$dstx = ($maxWidth - $newWidth) / 2;
$dsty = ($maxHeight - $newHeight) / 2;

$srcx = 0;
$srcy = 0;

imagecopy($im, $stamp, $dstx, $dsty, 0, 0, imagesx($stamp), imagesy($stamp));

header('Content-Type: image/jpeg');
imagejpeg($im);
imagedestroy($im);