<?php

namespace App\Providers;

use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

final class FakerImageProvider extends Base
{
    public function loremFlickr(
        FilesystemAdapter $storage,
        string $category,
        int $width = 500,
        int $height = 500,
        string $dir = ''
    ): string {
        $now = (string)Carbon::now()->getTimestampMs();
        $hash = md5(Str::random(9) . $now);
        $fileName = $hash . '.jpg';
        $fullPath = $dir . '/' . $fileName;
        $img = Image::make(file_get_contents("https://loremflickr.com/$width/$height/$category"));
        $img->orientate()->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->encode(null, 20);

        $storage->put(
            $fullPath,
            $img
        );
        return $fullPath;
    }
}
