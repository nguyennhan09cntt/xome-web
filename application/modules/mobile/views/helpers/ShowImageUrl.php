<?php

/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2/9/15
 * Time: 10:51 AM
 */
class Mobile_View_Helper_ShowImageUrl extends Zend_View_Helper_Abstract
{
    public function showImageUrl($image, $import = false, $width = 330, $crop = false)
    {
        $link = 'https://images1-focus-opensocial.googleusercontent.com/gadgets/proxy?container=focus&resize_w=' . $width;
        $path = $import ? '' : 'http://xome.ln3.in/upload/';

        if (!$path && !$image) {
            $image = 'no-image.png';
        }else if($path){
            if ($image){
                $position = strpos($image, '.');
                if ($crop == true) {
                    # scale 440 x 275
                    $image = substr_replace($image, '_440x275', $position, 0);
                }
            }


        }
        $image = $image ? $image : 'no-image.png';
        $fullPatch = sprintf(
            '%s&url=%s%s',
            $link,
            $path,
            $image
        );

        if ($crop == true) {
            $fullPatch = sprintf(
                '%s%s',
                $path,
                $image
            );
        }
        return $fullPatch;
    }
}