<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2/25/15
 * Time: 11:39 AM
 */
class Application_Function_Image
{
    static function renderCaptcha($code, $font, $position = -1, $width = 240, $height = 50)
    {
        $font_size = $height * 0.75;
        $image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');

        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 7, 133, 11);
        $noise_color = imagecolorallocate($image, 100, 100, 100);
        #generate random dots in background
        for ($i = 0; $i < ($width * $height) / 5; $i++) {
            imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
        }
        #generate random lines in background
        for ($i = 0; $i < ($width * $height) / 120; $i++) {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $background_color);
        }
        /* create $textBox and add text */

        if ($position != -1) {
            $n = strlen($code);
            $textBox = imagettfbbox($font_size, 0, $font, $code);
            $x = $font_size;
            $y = ($height - $textBox[5]) / 2;
            for ($i = 0; $i < $n; $i++) {
                $character = $code[$i];
                $paramTextColor = $text_color;
                if ($i == $position) {
                    $paramTextColor = imagecolorallocate($image, 255, 129, 25);
                }
                imagettftext($image, $font_size, 0, $x, $y, $paramTextColor, $font, $character);
                $x += $font_size;
            }
        } else {
            $textBox = imagettfbbox($font_size, 0, $font, $code);
            $x = ($width - $textBox[4]) / 2;
            $y = ($height - $textBox[5]) / 2;
            imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code);
        }

        /* output captcha image to browser */
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

    static function scale($originalFile, $targetFile, $newWidth = 150, $newHeight = 150)
    {
        $info = getimagesize($originalFile);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                throw Exception('Unknown image type.');
        }

        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);
        $tmpHeight = ($height / $width) * $newWidth;
        if ($newHeight < $tmpHeight) {
            $newWidth = ($width / $height) * $newHeight;
        } else {
            $newHeight = $tmpHeight;
        }
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $targetFile = $targetFile . '.' . $new_image_ext;
        if (file_exists($targetFile)) {
            unlink($targetFile);
        }
        if ($new_image_ext != 'gif') {
            $image_save_func($tmp, "$targetFile", 100);
        } else {
            $image_save_func($tmp, "$targetFile");
        }

    }

    static function crop($srcImage, $dstImage, $newWidth = 320, $newHeight = 320)
    {

        $info = getimagesize($srcImage);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func = 'imagejpeg';
                $new_image_ext = 'jpg';
                break;

            case 'image/png':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func = 'imagepng';
                $new_image_ext = 'png';
                break;

            case 'image/gif':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func = 'imagegif';
                $new_image_ext = 'gif';
                break;

            default:
                throw new Exception('Unknown image type.');
        }

        $image = $image_create_func($srcImage);

        list($width, $height) = getimagesize($srcImage);

        $original_aspect = $width / $height;
        $thumb_aspect = $newWidth / $newHeight;

        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $newHeight;
            $new_width = $width / ($height / $newHeight);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $newWidth;
            $new_height = $height / ($width / $newWidth);
        }

        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        // Resize and crop
        imagecopyresampled($thumb,
            $image,
            0 - ($new_width - $newWidth) / 2, // Center the image horizontally
            0 - ($new_height - $newHeight) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $width, $height);
        if ($new_image_ext != 'gif' && $new_image_ext != 'png') {
            $stamp = imagecreatefrompng(SYS_PATH . '/public/upload/water-mark.png');
            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($thumb, $stamp, imagesx($thumb) - $sx - $marge_right, imagesy($thumb) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            $image_save_func($thumb, "$dstImage", 100);
        } else {
            $stamp = imagecreatefrompng(SYS_PATH . '/public/upload/water-mark.png');
            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($thumb, $stamp, imagesx($thumb) - $sx - $marge_right, imagesy($thumb) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            $image_save_func($thumb, "$dstImage");
        }
    }
}