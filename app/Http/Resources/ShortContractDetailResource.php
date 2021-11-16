<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ShortContractDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'represent_company_hire'  =>  'represent_company_hire',
            'phone_company_hire'      =>  'phone_company_hire',
            'name_company_rental'     =>  'name_company_rental',
            'address_company_rental'  =>  'address_company_rental',
            'represent_company_rental'=>  'represent_company_rental',
            'phone_number_rental'     =>  'phone_number_rental',
            'tax'                     =>  'tax',
            'total_price'             =>  'total_price',
        ];
    }
}
