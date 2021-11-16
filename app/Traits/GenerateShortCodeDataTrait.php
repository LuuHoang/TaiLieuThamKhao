<?php

namespace App\Traits;

use App\Constants\Example;
use App\Constants\InputType;
use App\Models\AlbumModel;
use App\Models\CompanyModel;
use App\Models\SharedAlbumModel;

trait GenerateShortCodeDataTrait
{
    private function generateDataSharedAlbum(SharedAlbumModel $entity, $link, $password): array
    {
        return [
            'shared.link' => $link,
            'shared.password' => $password,
            'shared.guest.email' => $entity->email,
            'shared.guest.name' => $entity->full_name,
            'shared.guest.content' => $entity->message,
        ];
    }

    private function generateDataCompany(CompanyModel $companyEntity)
    {
        return [
            'company.company_name'      =>  $companyEntity->company_name,
            'company.company_code'      =>  $companyEntity->company_code,
            'company.address'           =>  $companyEntity->address,
            'company.representative'    =>  $companyEntity->representative,
            'company.tax_code'          =>  $companyEntity->tax_code,
            'company.logo_url'          =>  $companyEntity->logo_url,
        ];
    }

    private function generateDataAlbum(AlbumModel $albumEntity)
    {
        $currentUser = app('currentUser');
        $albumPropertyEntities = $currentUser->company->albumProperties;
        $albumData = [
            'album.album_type'    =>  $albumEntity->albumType->title,
            'album.image_url'     =>  $albumEntity->image_url,
            'album.user_creator'  =>  $albumEntity->user->full_name
        ];
        if ($albumPropertyEntities->isNotEmpty()) {
            $informationData = [];
            foreach ($albumPropertyEntities as $albumPropertyEntity) {
                $information = $albumEntity->albumInformations->where('album_property_id', $albumPropertyEntity->id)->first();
                $informationValue = $information->value ?? "";
                $informationData[$albumPropertyEntity->id] = [
                    'title'     =>  $albumPropertyEntity->title,
                    'value'     =>  $this->_convertInformationValue($informationValue, $albumPropertyEntity->type)
                ];
            }
            foreach ($informationData as $id => $info) {
                $albumData['album.information.' . $id . '.title'] = $info['title'];
                $albumData['album.information.' . $id . '.value'] = $info['value'];
            }
        }
        return $albumData;
    }

    private function _convertInformationValue($content, int $inputType)
    {
        $currentUser = app('currentUser');
        $contentConverted = !empty($content) ? $content : "";
        if ($inputType == InputType::DATE || $inputType == InputType::SHORT_DATE || $inputType == InputType::DATE_TIME) {
            if (!empty($content)) {
                $contentConverted = date(InputType::CONFIG[$inputType]['format'][$currentUser->userSetting->language], $content);
            } else {
                $contentConverted = Example::DATE_NONE;
            }
        }
        return $contentConverted;
    }
}
