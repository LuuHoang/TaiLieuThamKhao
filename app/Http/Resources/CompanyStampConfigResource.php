<?php


namespace App\Http\Resources;
use App\Constants\Disk;
use App\Constants\StampType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompanyStampConfigResource extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              =>  $this->id,
            'stamp_type'      =>  $this->stamp_type,
            'mounting_position' =>$this->mounting_position,
            'icon_path'         => Storage::disk(Disk::COMPANY)->url($this->icon_path),
            'text'              =>$this->text,
            'company_id'      =>  $this->company_id,
        ];
    }
}
