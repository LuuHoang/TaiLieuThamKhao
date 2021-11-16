<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AddtionalContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    =>    $this->pivot->id,
            'contract_id'           =>    $this->pivot->contract_id,
            'sample_contract_property_id'    =>    $this->pivot->sample_contract_property_id,
            'content'               => $this->pivot->content,
            'title'                 => $this->title,
            'data_type'                  => $this->data_type,
        ];
    }
}
