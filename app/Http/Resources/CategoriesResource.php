<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoriesResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'img' =>  is_null($this->img) ? null : Storage::disk('uploads')->url($this->img),
            'parent_id' => isset($this->parent_id) ? (int)$this->parent_id : null
        ];
    }
}
