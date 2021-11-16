<?php


namespace App\Http\Resources;


use App\Constants\Disk;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SampleContractPropertyResource;
use Illuminate\Support\Facades\Storage;

class SampleContractResource extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    =>    $this->id,
            'name_contract'         =>    $this->name_sample_contract,
            'description'	        =>    $this->description,
            'tags'                  =>    $this->tags,
            'category'              =>    $this->category,
            'created_by'            =>    $this->created_by,
            'updated_by'            =>    $this->updated_by,
            'content'               =>    $this->content,
            'sample_contract_properties' => SampleContractPropertyResource::collection($this->sampleContractProperties),
        ];
    }
}
