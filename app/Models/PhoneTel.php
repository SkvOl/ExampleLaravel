<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneTelGatewayPivot;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_phonetel', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneTel extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_tel';

    protected $dateFormat = 'U';

    public static $model_id = 61;

    protected $allowedQueries = [
        'id'=>['eq'],
        'number'=>['eq'],
        'inner_number'=>['eq'],
    ];

    protected $allowedOrderKeys = [
        'id',
        'number',
        'inner_number',
        'is_cell',
        'is_intercity',
        'is_hidden',
    ];

    public $timestamps = false;

    protected $fillable = [
        'number',
        'inner_number',
        'is_cell',
        'is_intercity',
        'is_hidden'
    ];

    const DELETED_AT = 'is_deleted';

    public function gateway()
    {
        return $this->hasMany(PhoneTelGatewayPivot::class, 'id_tel', 'id');
    }
}
