<?php

namespace App\Traits;

trait SortSearchFilterTrait
{
    public function sortByConditions($query, array $sortData, array $sortConditions)
    {
        foreach ($sortData as $key => $value) {
            if (!empty($sortConditions[$key]) && !empty($value)) {
                if (is_array($sortConditions[$key])) {
                    foreach ($sortConditions[$key] as $field) {
                        $query = $query->orderBy($field, $value);
                    }
                } else {
                    $query = $query->orderBy($sortConditions[$key], $value);
                }

                $this->currentSort = true;
            }
        }

        return $query;
    }

    public function filterByConditions($query, array $filterData, array $filterFields)
    {
        foreach ($filterData as $key => $value) {
            if (!empty($filterFields[$key]) && (!empty($value) || is_numeric($value))) {
                $query = $query->whereIn($filterFields[$key], explode(',', $value));
            }
        }

        return $query;
    }

    public function searchByConditions($query, array $searchData, array $searchFields)
    {
        $query->where(function ($query) use ($searchData, $searchFields) {
            $values = [];
            foreach ($searchData as $key => $value) {
                if (empty($searchFields[$key]) || empty($value)) {
                    continue;
                }
                $values = explode(' ', $value);
                break;
            }

            foreach ($values as $value) {
                $query->where(function ($query) use ($value, $searchData, $searchFields) {
                    $first = true;
                    foreach (array_keys($searchData) as $key) {
                        if (empty($searchFields[$key]) || empty($value)) {
                            continue;
                        }

                        if ($first) {
                            $query = $query->where(function ($query) use ($searchFields, $key, $value) {
                                if (is_array($searchFields[$key])) {
                                    foreach ($searchFields[$key] as $field) {
                                        $query->orWhere($field, 'LIKE', "%{$value}%");
                                    }
                                    return;
                                }
                                $query->where($searchFields[$key], 'LIKE', "%{$value}%");
                            });
                            $first = false;
                        } else {
                            $query = $query->orWhere(function ($query) use ($searchFields, $key, $value) {
                                if (is_array($searchFields[$key])) {
                                    foreach ($searchFields[$key] as $field) {
                                        $query->orWhere($field, 'LIKE', "%{$value}%");
                                    }
                                    return;
                                }
                                $query->where($searchFields[$key], 'LIKE', "%{$value}%");
                            });
                        }
                    }
                });
            }
            return $query;
        });

        return $query;
    }
}
