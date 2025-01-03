<?php

namespace App\Http\sources;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Http\sources\Paginator;
use Illuminate\Http\Request;
use App\Http\sources\Wrapper;

abstract class Transformer implements Responsable
{
    use Wrapper;

    /**
     * @var  string|null Корень ответа
     */
    protected $resourceKey = 'data';

    /**
     * @var  array|null Дополнительные параметры
     */
    protected $resourceAppends = ['status' => 'ok'];

    /**
     * @var  array Опциональные поля которые нужно добавить в ответ
     */
    protected $appends;

    /**
     * @var Collection|Paginator|object
     */
    private $data;

    public function __construct($data, array $appends = [])
    {
        $this->data = $data;
        $this->appends = $appends;
    }

    protected function needAppend($key)
    {
        return in_array($key, $this->appends) || isset($this->appends[$key]);
    }

    protected function getAppends()
    {
        return $this->appends;
    }

    protected function getNestedAppends($key)
    {
        return isset($this->appends[$key]) ? (array) $this->appends[$key] : [];
    }

    protected function performTransform($request)
    {
        if (is_null($this->data)) {
            return null;
        }

        $data = $this->data;

        if ($data instanceof Collection || $data instanceof Paginator) {
            return $data->map((function ($item) use ($request) {
                return $this->transform($item, $request);
            })->bindTo($this));
        } else {
            return $this->transform($this->data, $request);
        }
    }

    public function toArray($request)
    {
        $data = $this->performTransform($request);

        if ($data instanceof Paginator) {
            return $data->items();
        }

        return $data;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function toResponse($request)
    {   
        $data = $this->performTransform($request);
        return self::_response($data, 200);
    }


    /**
     * @param mixed $object
     * @param Request $request
     * @return array
     */
    abstract public function transform($object, Request $request);
}