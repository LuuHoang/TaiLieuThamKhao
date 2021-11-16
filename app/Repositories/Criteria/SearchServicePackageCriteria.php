<?php


namespace App\Repositories\Criteria;


use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchServicePackageCriteria implements CriteriaInterface
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
        $query = $model->select([
            '*',
            DB::raw("max_user * max_user_data as max_data")
        ]);
        if (!empty($this->_paramQuery['search'])) {
            $query = $query-> where(function ($query) {
                $query->where('title', 'LIKE', '%' . $this->_paramQuery['search'] . '%')
                    ->orWhere('description', 'LIKE' , '%' . $this->_paramQuery['search'] . '%');
            });
        }
        if(!empty($this->_paramQuery['sort']['id'])) {
            $query = $query->orderBy('id', $this->_paramQuery['sort']['id']);
        } else if(!empty($this->_paramQuery['sort']['title'])) {
            $query = $query->orderBy('title', $this->_paramQuery['sort']['title']);
        } else if(!empty($this->_paramQuery['sort']['description'])) {
            $query = $query->orderBy('description', $this->_paramQuery['sort']['description']);
        } else if(!empty($this->_paramQuery['sort']['price'])) {
            $query = $query->orderBy('price', $this->_paramQuery['sort']['price']);
        } else if(!empty($this->_paramQuery['sort']['user'])) {
            $query = $query->orderBy('max_user', $this->_paramQuery['sort']['user']);
        } else if(!empty($this->_paramQuery['sort']['data'])) {
            $query = $query->orderBy('max_data', $this->_paramQuery['sort']['data']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }
        return $query;
    }
}
