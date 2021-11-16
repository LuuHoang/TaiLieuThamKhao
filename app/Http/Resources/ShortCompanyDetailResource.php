<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShortCompanyDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'company_name'      =>  $this[0]->company_name,
            'company_code'      =>  $this[0]->company_code,
            'address'           =>  $this[0]->address,
            'representative'    =>  $this[0]->representative,
            'tax_code'          =>  $this[0]->tax_code,
            'logo_url'          =>  $this[0]->logo_url,
            'phone'             =>  $this[0]->phone,
        ];
    }
}
