<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneGatewayPort;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_telgateway', description: 'id записи телефона и шлюза', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneTelGateway extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;
    protected $table = 'lk.phone_tel_gateway';
    protected $dateFormat = 'U';
    public static $model_id = 62;
    protected $allowedOrderKeys = [
        'id',
        'id_tel',
        'id_port_gateway'
    ];
    protected $fillable = [
        'id_tel',
        'id_port_gateway'
    ];
    const DELETED_AT = 'is_deleted';
    public function phoneTel()
    {
        return $this->belongsTo(PhoneTel::class, 'id_tel','id');
    }
    public function gatewayPlint()
    {
        
    }
    public function gatewayPort()
    {
        return $this->belongsTo(PhoneGatewayPlint::class, 'id_port_gateway','id_port_gateway');
    }
}
