<?php


namespace App\Services;


class ImageBase64Service
{
    public static function imageUpload($image, $namePrefix, $destination)
    {
        list($type, $file) = explode(';', $image);
        list(, $extension) = explode('/', $type);
        list(, $file) = explode(',', $file);
        $fileNameToStore = $namePrefix . rand(1, 100000000) . '.' . $extension;
        $source = fopen($image, 'r');
        $destination = fopen($destination . $fileNameToStore, 'w');
        stream_copy_to_stream($source, $destination);
        fclose($source);
        fclose($destination);
        return $fileNameToStore;
    }

    public static function imageResizeUpload($image, $namePrefix, $destination)
    {
        list($type, $file) = explode(';', $image);
        list(, $extension) = explode('/', $type);
        list($dd , $file) = explode(',', $file);
        $image_resource = imagecreatefromstring(base64_decode($file));
        $resized_image = imagescale($image_resource, 500,300);
        $fileNameToStore = $namePrefix . rand(1, 100000000) . '.' . $extension;
        imagejpeg($resized_image, $destination.$fileNameToStore);
        return $fileNameToStore;
    }
}
