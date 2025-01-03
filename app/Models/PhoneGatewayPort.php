<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneGatewayPlintPivot;
use App\Models\PhoneGateway;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_gatewayport', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneGatewayPort extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;


    protected $table = 'lk.phone_gateway_port';
    protected $dateFormat = 'U';

    public static $model_id = 65;

    protected $allowedOrderKeys = [
        'id',
        'id_gateway',
        'name',
    ];

    protected $fillable = [
        'id_gateway',
        'name'
    ];

    const DELETED_AT = 'is_deleted';

    public function gateway()
    {
        return $this->belongsTo(PhoneGateway::class, 'id_gateway', 'id');
    }
    
    public function gatewayPlint()
    {
        return $this->hasMany(PhoneGatewayPlintPivot::class, 'id', 'id_port_gateway');
    }
}
