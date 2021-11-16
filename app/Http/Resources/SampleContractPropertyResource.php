<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SampleContractPropertyResource extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  =>    $this->id,
            'title'               =>    $this->title,
            'data_type'	          =>    $this->data_type,
            'sample_contract_id'  =>    $this->sample_contract_id,
        ];
    }
}
