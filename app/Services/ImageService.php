<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * @param string $filePathFromRequest
     * @param string $dirName
     * @return string
     */
    public function saveImageWithResize(string $filePathFromRequest, string $dirName): string
    {
        $img = Image::make($filePathFromRequest);
        $img->orientate()->fit(300, 350, function ($constraint) {
            $constraint->upsize();
        })->encode(null, 100);
        $now = Carbon::now()->toDateTimeString();
        $hash = md5($img->__toString() . $now);
        $fileName = $dirName . '/' . $hash . '.jpg';
        Storage::disk('uploads')->put($fileName, $img);
        return $fileName;
    }
}
