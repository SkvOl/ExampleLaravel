<?php

namespace App\Http\sources;


use Illuminate\Http\Request;

trait ConfigurableTrait
{
    /**
     * @return array Разрешенные поля для сортировки
     */
    public function getAllowedOrderKeys()
    {
        return $this->allowedOrderKeys ?? [];
    }

    /**
     * @return array Поля доступные для кастомных where
     * Указываются в формате
     * [
     *   'id' => ['between', 'lt', 'gt'],
     *   'created_at' => 'between'
     * ]
     */
    public function getAllowedQueries()
    {
        return $this->allowedQueries ?? [];
    }

    public function getNullValueConditions()
    {
        return $this->nullValueConditions ?? [];
    }

    /**
     * @return int Максимальнео число результатов на страницу
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage ?? 200;
    }

    /**
     * @return int Минимальное число результатов на страницу
     */
    public function getMinPerPage()
    {
        return $this->minPerPage ?? 1;
    }

    /**
     * @return int Дефолтное количество результатов на странице
     */
    public function getDefaultPerPage()
    {
        return $this->defaultPerPage ?? 30;
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new ConfigurableBuilder($query);
    }

    /**
     * @param Request $request
     * @return ConfigurableBuilder
     */
    public static function configure(Request $request)
    {
        return (new static)->newQuery()->configure($request->only(['query', 'order', 'per_page', 'page']));
    }

}