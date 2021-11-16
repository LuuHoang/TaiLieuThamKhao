<?php

use App\Models\CompanyModel;
use App\Models\AlbumTypeModel;
use Illuminate\Database\Seeder;
use App\Models\AlbumPropertyModel;
use App\Models\MediaPropertyModel;
use App\Models\LocationPropertyModel;

class UpdateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = CompanyModel::all();
        foreach($companies as $company){
            $albumTypeByCompany = $company->albumTypes;
            $default = $company->albumTypes->first();
            if (!empty($default)) {
                $id = $default->id;
                //update table AlbumType
                $default->update(['default' => 1]);

                // update MediaProperty  LocationProperty AlbumProperty
                $mediaProperties = MediaPropertyModel::where('company_id', $default->company_id)->get()->toArray();
                $locationProperties = LocationPropertyModel::where('company_id', $default->company_id)->get()->toArray();
                $albumProperties = AlbumPropertyModel::where('company_id', $default->company_id)->get()->toArray();
                foreach($albumTypeByCompany as $key => $albumType){
                    if($key === 0){
                        MediaPropertyModel::where('company_id', $default->company_id)->update(['album_type_id' => $id]);
                        AlbumPropertyModel::where('company_id', $default->company_id)->update(['album_type_id' => $id]);
                        LocationPropertyModel::where('company_id', $default->company_id)->update(['album_type_id' => $id]);
                    } else {
                        $albumTypeId = $albumType->id;
                        $newMediaProps = [];
                        $newAlbumProps = [];
                        $newLocationProps = [];

                        foreach($mediaProperties as $mediaProperty){
                            unset($mediaProperty['id']);
                            $newMediaProps = array_merge($mediaProperty,[
                                'album_type_id' => $albumTypeId,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                        DB::table('media_properties')->insert($newMediaProps);

                        foreach($albumProperties as $albumProperty){
                            unset($albumProperty['id']);
                            $newAlbumProps = array_merge($albumProperty,[
                                'album_type_id' => $albumTypeId,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                        DB::table('album_properties')->insert($newAlbumProps);

                        foreach($locationProperties as $locationProperty){
                            unset($locationProperty['id']);
                            $newLocationProps = array_merge($locationProperty,[
                                'album_type_id' => $albumTypeId,
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]);
                        }
                        DB::table('location_properties')->insert($newLocationProps);

                    }
                }
            }
        }
    }
}
