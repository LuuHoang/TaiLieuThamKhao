<?php

namespace App\Providers;

use App\Constants\ColumnExcelUser;
use App\Supports\Components\Image\ImageUtil;
use App\Supports\Components\Response\ResponseFormat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('response', function () {
            return new ResponseFormat;
        });

        $this->app->singleton('image', function () {
            return new ImageUtil;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('import_unique', function ($attribute, $value, $parameters, $validator) {
            $dataValidate = array_values($validator->getData())[0];
            $table = null;
            $column = null;
            $wheres = [];
            foreach ($parameters as $key => $parameter) {
                if ($key === 0) {
                    $table = $parameter;
                } else if ($key === 1) {
                    $column = $parameter;
                } else if ($key % 2 === 0) {
                    $wheres[] = [$parameter, '=', strtolower($parameters[$key + 1]) == 'null' ? null : $parameters[$key + 1]];
                }
            }
            $countRecord = DB::table($table)->where($wheres)->where($column, '=', $value)->where('email', '<>', $dataValidate[Str::slug(ColumnExcelUser::TITLES[ColumnExcelUser::EMAIL], '_')])->get()->count();
            if ($countRecord === 0) {
                return true;
            }
            return false;
        });
    }
}
