<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ListAllSampleContractResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    =>  $this->id,
            'name_sample_contract'  =>  $this->name_sample_contract,
            'description'           =>  $this->description,
            'tags'                  =>  $this->tags,
            'category'              =>  $this->category,
            'content'               =>  $this->content,
            'addition'              =>  SampleContractPropertyResource::collection($this->sampleContractProperties)
        ];
    }
}
