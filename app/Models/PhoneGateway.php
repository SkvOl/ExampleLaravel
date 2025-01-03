<?php

namespace App\Models;

use App\Http\sources\SoftDeleteFlagTrait;
use App\Http\sources\ConfigurableTrait;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_phonegateway', description: 'id записи телефона и шлюза', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneGateway extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_gateway';

    protected $dateFormat = 'U';

    public static $model_id = 64;

    protected $allowedOrderKeys = [
        'id',
        'name',
        'ip',
        'ip_out',
        'id_room',
        'count_port'
    ];

    protected $fillable = [
        'name',
        'ip',
        'ip_out',
        'id_room',
        'count_port'
    ];

    const DELETED_AT = 'is_deleted';
    
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
