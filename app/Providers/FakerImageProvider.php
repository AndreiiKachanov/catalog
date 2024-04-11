<?php

namespace App\Providers;

use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

final class FakerImageProvider extends Base
{

    public function loremflickr($storage, string $dir = '', int $width = 500, int $height = 500, string $category): string
    {
        $now = (string)Carbon::now()->getTimestampMs();
        // dump($now);
        $hash = md5(Str::random(9) . $now);

        $fileName = $hash . '.jpg';
        // $filePath = $storage->path($dir) . '/' . $fileName;
        // dd($filePath);

        $fullPath = $dir . '/' . $fileName;




        $img = Image::make(file_get_contents("https://loremflickr.com/$width/$height/$category"));
        $img->orientate()->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->encode(null, 20);
        // dd($img);

        $storage->put(
            $fullPath,
            // file_get_contents("https://loremflickr.com/$width/$height/$category")
            $img
        );

        // dd($dir . '/' . Str::random(9) . '.jpg';);
        return $fullPath;
    }

}
