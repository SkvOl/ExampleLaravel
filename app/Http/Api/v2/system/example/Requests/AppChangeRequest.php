<?php
namespace App\Http\Api\v2\system\example\Requests;

use OpenApi\Attributes as OAT;
use App\Http\sources\Request;

#[OAT\RequestBody(
    request: 'AppChangeRequest',
    required: true,
    content: new OAT\JsonContent(properties: [
        new OAT\Property(property: 'id_execution', type: 'int', format: 'int', example: '123456', schema:'int|string'),
        new OAT\Property(property: 'id_level', type: 'int', format: 'int', example: '2', schema:'int|string|min:2|max:93'),
        new OAT\Property(property: 'id_source', type: 'int', format: 'int', example: '4', schema:'int|string|min:1|max:4'),
        new OAT\Property(property: 'guid_app', type: 'string', format: 'string', example: 'df4r3-g43ffg4-ds32dg-h5h5', schema:'string'),
    ])
)]
class AppChangeRequest extends Request{

    function rules(): array{

        return [
            'id_execution'=>'integer',
            'id_level'=>'integer|min:2|max:93',
            'id_source'=>'integer|min:1|max:4',
            'guid_app'=>'string'
        ];
    }
}
