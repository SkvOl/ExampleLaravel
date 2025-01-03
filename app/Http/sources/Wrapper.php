<?php

namespace App\Http\sources;

use OpenApi\Attributes as OAT;



#[OAT\Parameters(name: 'paginator', parameters: [
    new OAT\Parameter(name: 'page', parameter: 'page', description: 'Текущая страница', in: 'query', required: false, deprecated: false, allowEmptyValue: true),
    new OAT\Parameter(name: 'per_page', parameter: 'per_page', description: 'Сколько эл-в нужно вывести на одной странице', in: 'query', required: false, deprecated: false, allowEmptyValue: true),
    new OAT\Parameter(name: 'order', parameter: 'order', description: 'Последовательность', in: 'query', required: false, deprecated: false, allowEmptyValue: true),
    new OAT\Parameter(name: 'query', parameter: 'query', description: 'Cмещение и ограничение вычислений', in: 'query', required: false, deprecated: false, allowEmptyValue: true),
])]
trait Wrapper
{

    static private $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => '*',
        'Access-Control-Allow-Credentials' => 'true',
    ];

    #[OAT\Schema(
        schema: 'Paginator',
        properties: [
            new OAT\Property(property: 'current_page', type: 'int', format: 'int', example: '1'),
            new OAT\Property(property: 'from', type: 'int', format: 'int', example: '1'),
            new OAT\Property(property: 'last_page', type: 'int', format: 'int', example: '380'),
            new OAT\Property(property: 'per_page', type: 'int', format: 'int', example: '30'),
            new OAT\Property(property: 'to', type: 'int', format: 'int', example: '30'),
            new OAT\Property(property: 'total', type: 'int', format: 'int', example: '11371'),
        ]
    )]

    function toArray(): array
    {
        return [
            'status' => 'Successfully',
            'paginator' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),

            ],
            'data' => $this->items,
        ];
    }

    static function _response($response = null, $statusCode = 200)
    {
        $status = (in_array($statusCode, [200, 201, 304]) ? 'Successfully' : 'Error');

        if ($response instanceof Paginator) return response($response)->setStatusCode($statusCode)->withHeaders(self::$headers);
        elseif (is_null($response)) return response()->noContent()->setStatusCode($statusCode);
        else return response([
            'status' => $status,
            'data' => $response,

        ])->setStatusCode($statusCode)->withHeaders(self::$headers);
    }
}