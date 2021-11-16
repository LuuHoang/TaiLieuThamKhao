<?php

namespace App\Repositories\Contracts;

use App\Models\AbstractModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 * @package App\Repositories\Contracts
 */
interface RepositoryInterface
{
    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']);

    /**
     * @param int   $limit
     * @param array $columns
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit, array $columns = ['*']);

    /**
     * @param array $columns
     *
     * @return AbstractModel
     */
    public function first(array $columns = ['*']);

    /**
     * @param array $data
     *
     * @return AbstractModel
     */
    public function create(array $data);

    /**
     * @param $attributes
     * @param $values
     *
     * @return AbstractModel
     */
    public function updateOrCreate($attributes, $values);

    /**
     * @param array $wheres
     * @param array $data
     *
     * @return bool
     */
    public function update(array $wheres, array $data);

    /**
     * @param array $wheres
     *
     * @return int
     */
    public function delete(array $wheres = []);

    /**
     * @param array $relations
     *
     * @return $this
     */
    public function with(array $relations);

    /**
     * @param int   $id
     * @param array $columns
     *
     * @return AbstractModel|null
     */
    public function find(int $id, array $columns = ['*']);

    /**
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function where(string $field, string $operator, $value);

    /**
     * @param string $field
     * @param array  $values
     *
     * @return $this
     */
    public function whereIn(string $field, array $values);

    /**
     * @param string $field
     * @param array  $values
     *
     * @return $this
     */
    public function whereNotIn(string $field, array $values);

    /**
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc');

    /**
     * Apply criteria in current Query
     *
     * @return $this
     */
    public function applyCriteria();

    /**
     * @param $criteria
     *
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria);

    /**
     * @param CriteriaInterface $criteria
     *
     * @return Collection
     */
    public function getByCriteria(CriteriaInterface $criteria);

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria();
}
