<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;


trait HandlesImages
{
    public function uploadImage($image, $path, $variants = false, $extension = 'png')
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

        Storage::disk('public')->makeDirectory($path);

        $fileName = Str::uuid()->toString();
        $pathname = $path . '/' . $fileName . '.' . $extension;

        Storage::disk('public')->put($pathname, $fileData);

        if ($variants) {
            $this->processAndSaveImage($fileData, 300, $path, $fileName, $extension);
        }

        return $pathname;
        //
    }

    public function processAndSaveImage($stream, $width, $imagePath, $fileName, $extension)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($stream)->scaleDown($width);
        $path = $imagePath . '/thumbnails/' . $fileName . '.' . $extension;

        Storage::disk('public')->put($path, (string) $image->encode());
    }


    public function deleteImage($name, $path, $variant = false)
    {
        unlink(public_path($path . '/storage' . '/' . $name));

        if ($variant) {
            unlink(public_path($path . '/thumbnails/' . $name));
        }
    }
}
