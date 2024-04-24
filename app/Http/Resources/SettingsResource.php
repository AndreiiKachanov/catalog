<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
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
