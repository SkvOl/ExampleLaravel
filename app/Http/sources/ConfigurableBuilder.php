<?php

namespace App\Http\sources;

use Illuminate\Database\Eloquent\Builder;
use Psr\Log\InvalidArgumentException;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ConfigurableBuilder extends Builder
{
    protected $requestedPerPage;
    protected $requestedPage;

    public function configure(array $request)
    {
        $model = $this->model;

        if (isset($request['order'])) {
            $allowedOrderKeys = $model->getAllowedOrderKeys();
            $orderKeys = json_decode($request['order']);
            // dd($orderKeys);
            // dd( $request['order']);
            foreach ($orderKeys as $key => $desc) {
                $orderKey = null;
                foreach ($allowedOrderKeys as $keyTable => $allowedOrderKey) {
                    if ($key === $allowedOrderKey) {
                        $orderKey = $model->getTable() . '.' . $key;
                        break;
                    } else if (is_array($allowedOrderKey)) {
                        if (in_array($key, $allowedOrderKey)) {
                            $orderKey = $keyTable ? $keyTable . '.' . $key : $key;
                            break;
                        }
                    }
                }
                if (is_null($orderKey))
                    throw new \InvalidArgumentException("$key is not valid key for order");

                $this->orderBy($orderKey, strtolower($desc) == 'asc' ? 'asc' : 'desc');
            }
        }

        if (isset($request['query'])) {
            $allowedQueries = $model->getAllowedQueries();
            $queryArray = json_decode($request['query'], true);

            $nullValueConditions = $model->getNullValueConditions();

            foreach ($queryArray as $key => $params) {

                if (!is_array($params))
                    throw new \InvalidArgumentException("$key query value is invalid");

                $allowedQueryTypes = null;
                $queryKey = $key;
                $queryTable = $model->getTable();
                $keyParts = explode('.', $key);

                if (count($keyParts) > 1 && array_key_exists($keyParts[0], $allowedQueries)) {
                    $allowedQueryValues = $allowedQueries[$keyParts[0]];
                    if (is_array($allowedQueryValues)) {
                        if (array_key_exists($keyParts[1], $allowedQueryValues)) {
                            $queryTable = $keyParts[0];
                            $queryKey = $keyParts[1];
                            $allowedQueryTypes = (array)$allowedQueryValues[$keyParts[1]];
                        }
                    }
                } else if (array_key_exists($key, $allowedQueries)){
                    $allowedQueryTypes = (array)$allowedQueries[$key];
                }

                if (is_null($allowedQueryTypes))
                    throw new \InvalidArgumentException("$key is not valid key for query");

                $this->processQueryModifier($queryKey, $params, $queryTable, $allowedQueryTypes, $nullValueConditions);
            }
        }

        if (isset($request['per_page'])) {
            $perPage = intval($request['per_page']);
            $perPage = max($perPage, $model->getMinPerPage());
            $perPage = min($perPage, $model->getMaxPerPage());
            $this->requestedPerPage = $perPage;
        }

        if (isset($request['page'])) {
            $this->requestedPage = intval($request['page']);
        }

        return $this;
    }

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?? $this->requestedPage ?? 1;

        $perPage = $perPage ?? $this->requestedPerPage ?? $this->model->getDefaultPerPage();

        $results = ($total = $this->toBase()->getCountForPagination())
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(Paginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    private function processQueryModifier($key, array $params, $table, $keyTypes, $nullValueConditions)
    {
        $simpleTypes = [
            'eq' => '=', 'not_eq' => '!=', 'lt' => '<',
            'lte' => '<=', 'gt' => '>',
            'gte' => '>=', 'like' => 'LIKE'
        ];

        $compositeTypes = ['scoped', 'in', 'not_in', 'between', 'not_between', 'search', 'null', 'not_null', 'period_days', 'starts_with', 'ends_with', 'has', 'doesnt_have', 'has_attr', 'doesnt_have_attr'];

        $type = isset($params['type']) && is_scalar($params['type']) ? strtolower($params['type']) : null;
        if (
            is_null($type) ||
            (!in_array($type, array_keys($simpleTypes)) && !in_array($type, $compositeTypes)) ||
            !in_array($type, $keyTypes)
        ) {
            throw new \InvalidArgumentException("$key query type is not valid");
        }

        $baseKey = $key;

        if (strpos($key, '>') !== false) {
            $column = Str::before($baseKey, '>');
            $jsonKey = Str::after($baseKey, '>');
            $key = '"' . $table . '"."' . $column . '"' . "->>'" . $jsonKey . "'";
        } else
            $key = $table . '.' . $key;

        if (array_key_exists($key, $nullValueConditions))
            $key = "coalesce(" . $key . "," . $nullValueConditions[$key] . ")";

        if (in_array($type, ['search', 'starts_with', 'ends_with']))
            $key = 'lower(' . $key . ')';

        $key = DB::raw($key);

        $value = $params['value'] ?? null;
        if (isset($simpleTypes[$type])) {
            $this->where($key, $simpleTypes[$type], $value);
        } else {
            switch ($type) {
                case 'scoped':
                    $this->scopes([$baseKey => $value]);
                    break;
                case 'in':
                    $this->whereIn($key, (array)$value);
                    break;
                case 'not_in':
                    $this->whereNotIn($key, (array)$value);
                    break;
                case 'between':
                    $this->whereBetween($key, (array)$value);
                    break;
                case 'not_between':
                    $this->whereBetween($key, (array)$value, 'and', true);
                    break;
                case 'has':
                    $this->whereHas($baseKey, function ($q) use ($value){
                        $q->where('id', $value);
                    });
                    break;
                case 'doesnt_have':
                    $this->whereDoesntHave($baseKey, function ($q) use ($value){
                        $q->where('id', $value);
                    });
                    break;
                case 'search':
                    $value = mb_strtolower(strval($value));
                    $this->where($key, 'LIKE', "%{$value}%");
                    break;
                case 'starts_with':
                    $value = mb_strtolower(strval($value));
                    $this->where($key, 'LIKE', "{$value}%");
                    break;
                case 'ends_with':
                    $value = mb_strtolower(strval($value));
                    $this->where($key, 'LIKE', "%{$value}");
                    break;
                case 'null':
                    $this->whereNull($key);
                    break;
                case 'not_null':
                    $this->whereNotNull($key);
                    break;
                case 'period_days':
                    $this->whereDate($key, '>=', Carbon::today()->subDays($value)->toDateString());
                    break;
                case 'has_attr':
                    $this->whereRaw('((' . $key . ' & ?) = ?)', [intval($value), intval($value)]);
                    break;
                case 'doesnt_have_attr':
                    $this->whereRaw('((' . $key . ' & ?) <> ?)', [intval($value), intval($value)]);
                    break;
            }
        }
    }
}