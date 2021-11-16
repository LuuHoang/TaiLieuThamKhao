<?php

namespace App\Repositories\Criteria;

use App\Constants\AlbumProperty;
use App\Constants\Boolean;
use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchAlbumsCriteria implements CriteriaInterface
{
    private $_paramQuery;

    public function __construct(Array $paramQuery)
    {
        $this->_paramQuery = $paramQuery;
    }

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $albumHighlight = DB::table('album_informations')
            ->join('album_properties', function ($join) {
                $join->on('album_informations.album_property_id', '=', 'album_properties.id')
                    ->where('album_properties.highlight', '=', Boolean::TRUE)
                    ->whereNull('album_informations.deleted_at')
                    ->whereNull('album_properties.deleted_at');
            })->select('album_informations.album_id', 'album_informations.value');

        $query = $model
            ->leftJoin('album_informations', function($join) {
                $join->on('albums.id', '=', 'album_informations.album_id')
                    ->whereNull('album_informations.deleted_at');
            })
            ->leftJoin('album_properties', function ($join) {
                $join->on('album_informations.album_property_id', '=', 'album_properties.id')
                    ->whereNull('album_properties.deleted_at');
            })
            ->leftJoinSub($albumHighlight, 'highlight', function ($join) {
                $join->on('albums.id', '=', 'highlight.album_id');
            })
            ->leftJoin('users', function ($join) {
                $join->on('albums.user_id', '=', 'users.id')
                    ->whereNull('users.deleted_at');
            })
            ->join('album_types', function($join) {
                $join->on('albums.album_type_id', '=', 'album_types.id')
                    ->whereNull('album_types.deleted_at');
            });

        if (!empty($this->_paramQuery['search'])) {
            $arraySearch = explode(" ",$foo = preg_replace('/\s+/', ' ', $this->_paramQuery['search']));
            if (count($arraySearch) > 0) {
                $query = $query-> where(function ($query) use ($arraySearch){
                    foreach ($arraySearch as $key => $searchKey) {
                        if ($key == 0) {
                            $query = $query->where('album_types.title', 'like', '%' . $searchKey . '%')
                                ->orWhere('album_informations.value', 'like', '%' . $searchKey . '%');
                        } else {
                            $query = $query->orWhere('album_types.title', 'like', '%' . $searchKey . '%')
                                ->orWhere('album_informations.value', 'like', '%' . $searchKey . '%');
                        }
                    }
                });
            }
        }

        if (!empty($this->_paramQuery['filter']['album_type_ids'])) {
            $albumTypeIds = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['album_type_ids']))));
            $query = $query->whereIn('albums.album_type_id', $albumTypeIds);
        }

        if (!empty($this->_paramQuery['filter']['user_ids'])) {
            $userIds = array_unique(array_values(array_filter(explode(",",$this->_paramQuery['filter']['user_ids']))));
            $query = $query->whereIn('albums.user_id', $userIds);
        }

        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('albums.id', $this->_paramQuery['sort']['id']);
        } elseif (!empty($this->_paramQuery['sort']['date'])) {
            $query = $query->orderBy('albums.created_at', $this->_paramQuery['sort']['date']);
        } elseif (!empty($this->_paramQuery['sort']['user'])) {
            $query = $query->orderBy('users.full_name', $this->_paramQuery['sort']['user']);
        } elseif (!empty($this->_paramQuery['sort']['highlight'])) {
            $query = $query->orderBy('highlight.value', $this->_paramQuery['sort']['highlight']);
        } else {
            $query = $query->orderBy('albums.created_at', 'DESC');
        }

        return $query
            ->select([
                'albums.id',
                'albums.user_id',
                'albums.album_type_id',
                'albums.image_path',
                'albums.size',
                'albums.created_at',
                'albums.updated_at',
                'albums.show_comment'
            ])
            ->groupBy([
                'albums.id',
                'albums.user_id',
                'albums.album_type_id',
                'albums.image_path',
                'albums.size',
                'albums.created_at',
                'albums.updated_at',
                'albums.show_comment',
                'highlight.value'
            ]);
    }
}
