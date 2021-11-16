<?php

namespace App\Repositories;

use App\Models\AbstractModel;
use App\Repositories\Contracts\CriteriaInterface;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class Repository
 * @package App\Repositories
 */
class Repository implements RepositoryInterface
{
    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * Collection of Criteria
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * Repository constructor.
     *
     * @param AbstractModel $model
     */
    public function __construct(AbstractModel $model)
    {
        $this->model = $model;
        $this->criteria = new Collection();
    }

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*'])
    {
        $this->applyCriteria();
        $result = $this->model->get($columns);
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        $this->applyCriteria();
        $result = $this->model->count();
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $limit, array $columns = ['*'])
    {
        $this->applyCriteria();
        $result = $this->model->paginate($limit, $columns);
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function first(array $columns = ['*'])
    {
        $this->applyCriteria();
        $result = $this->model->first($columns);
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        $this->resetModel();

        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreate($attributes, $values)
    {
        $this->resetModel();

        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @inheritDoc
     */
    public function update(array $wheres, array $data)
    {
        if (count($wheres)) {
            $model = $this->model;

            foreach ($wheres as $where) {
                $model = $model->where($where[0], $where[1], $where[2]);
            }

            return $model->update($data);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function updateOr(array $wheres, array $data)
    {
        if (count($wheres)) {
            $model = $this->model;

            foreach ($wheres as $where) {
                $model = $model->orWhere($where[0], $where[1], $where[2]);
            }

            return $model->update($data);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function delete(Array $wheres = [])
    {
        $model = $this->model;

        if (count($wheres)) {
            foreach ($wheres as $where) {
                $model = $model->where($where[0], $where[1], $where[2]);
            }
        }

        return $model->delete();
    }

    /**
     * @inheritDoc
     */
    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function find(int $id, array $columns = ['*'])
    {
        $result = $this->model->find($id, $columns);
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function where(string $field, string $operator, $value)
    {
        $this->model = $this->model->where($field, $operator, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereIn(string $field, array $values)
    {
        $this->model = $this->model->whereIn($field, $values);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function whereNotIn(string $field, array $values)
    {
        $this->model = $this->model->whereNotIn($field, $values);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria) {
            foreach ($criteria as $c) {
                if ($c instanceof CriteriaInterface) {
                    $this->model = $c->apply($this->model, $this);
                }
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function pushCriteria(CriteriaInterface $criteria)
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getByCriteria(CriteriaInterface $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        $result = $this->model->get();
        $this->resetModel();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function resetCriteria()
    {
        $this->criteria = new Collection();

        return $this;
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return void
     */
    protected function resetModel(): void
    {
        $this->model = $this->model->getModel();
    }
}
