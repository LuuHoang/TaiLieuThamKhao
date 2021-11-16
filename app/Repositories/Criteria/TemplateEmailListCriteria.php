<?php

namespace App\Repositories\Criteria;

use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use App\Traits\SortSearchFilterTrait;
use Illuminate\Database\Query\JoinClause;

class TemplateEmailListCriteria implements CriteriaInterface
{
    use SortSearchFilterTrait;

    private $sort;
    private $keyword;

    public function __construct(array $sort, ?string $keyword)
    {
        $this->sort = $sort;
        $this->keyword = $keyword;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->leftJoin('users', function (JoinClause $query) {
            $query->on('users.id', '=', 'template_emails.created_user');
        })->groupBy([
            'template_emails.id',
            'users.id',
        ])->select([
            'template_emails.*',
            'users.full_name',
        ]);
        $model = $this->searchByConditions($model, [
            'title' => $this->keyword,
        ], [
            'title' => 'template_emails.title',
        ]);
        $model = $this->sortByConditions($model, $this->sort, [
            'id' => 'template_emails.id',
            'name' => 'users.full_name',
            'title' => 'template_emails.title',
        ]);

        return $model;
    }
}
