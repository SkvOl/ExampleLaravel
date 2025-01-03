<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneSocket;
use App\Models\PhoneGatewayPlintPivot;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_plintportsocket', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhonePlintPortSocketPivot extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_plint_port_socket';

    protected $dateFormat = 'U';

    public static $model_id = 68;

    protected $allowedOrderKeys = [
        'id',
        'id_port_plint',
        'id_socket'
    ];

    protected $fillable = [
        'id_port_plint',
        'id_socket'
    ];

    const DELETED_AT = 'is_deleted';

    public function plintSocket()
    {
        return $this->belongsTo(PhoneSocket::class, 'id_socket', 'id');
    }

    public function gatewayPlint()
    {
        return $this->belongsTo(PhoneGatewayPlintPivot::class, 'id_port_plint', 'id_port_plint');
    }
    
    // public function plintPort()
    // {
    //     return $this->belongsTo(PhonePlintPort::class,'id_port_plint','id');
    // }
}
