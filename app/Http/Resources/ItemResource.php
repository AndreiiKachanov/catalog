<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ItemResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'note' => $this->note,
            'article_number' => $this->article_number,
            'price' => $this->price,
            'min_order_amount' => $this->min_order_amount,
            'img' => Storage::disk('uploads')->url($this->img),
            'is_new' => $this->is_new,
            'is_hit' => $this->is_hit,
            'is_bestseller' => $this->is_bestseller,
            'category' => (int)$this->category_id
        ];
    }
}
