<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'prop_key'   => $this->prop_key,
            'title'      => $this->title,
            'prop_value' => $this->prop_value,
            'fa_icon'    => $this->fa_icon,
            'is_url' => $this->is_url
        ];
    }
}
