<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneGatewayPort;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_gatewayplint', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneGatewayPlint extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;
    protected $table = 'lk.phone_gateway_plint';
    protected $dateFormat = 'U';
    public static $model_id = 63;
    protected $allowedOrderKeys = [
        'id',
        'id_port_gateway',
        'id_port_plint',
    ];
    protected $fillable = [
        'id_port_gateway',
        'id_port_plint'
    ];
    const DELETED_AT = 'is_deleted';

    public function gatewayPort()
    {
        return $this->belongsTo(PhoneGatewayPort::class, 'id');
    }
    public function portPlint()
    {
        return $this->belongsTo(PhonePlintPort::class, 'id');
    }
}
