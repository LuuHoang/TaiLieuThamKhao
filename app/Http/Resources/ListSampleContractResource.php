<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ListSampleContractResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    =>  $this->id,
            'created_at'            =>  $this->created_at,
            'name_sample_contract'  =>  $this->name_sample_contract,
            'description'           =>  $this->description,
            'tags'                  =>  $this->tags,
            'created_by'            =>  $this->created_by,
            'total_contracts'        =>  $this->totalContracts,
            'category'              =>  $this->category,
        ];
    }
}
