<?php

namespace App\Http\Resources;

use App\Models\UserSettingModel;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserSettingResource
 * @package App\Http\Resources
 * @mixin UserSettingModel
 */
class UserSettingResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'image_size'    => $this->image_size,
            'language'      => $this->language,
            'voice'         => $this->voice,
        ];
    }
}
