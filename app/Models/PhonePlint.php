<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Http\sources\SoftDeleteFlagTrait;
use App\Models\PhonePlintPort;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_phoneplint', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhonePlint extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_plint';

    protected $dateFormat = 'U';

    public static $model_id = 66;

    protected $allowedOrderKeys = [
        'id',
        'name',
        'id_room',
        'count_port',
    ];

    protected $fillable = [
        'name',
        'id_room',
        'count_port'
    ];

    const DELETED_AT = 'is_deleted';

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }
    
    public function plintPort()
    {
        return $this->hasMany(PhonePlintPort::class, 'id', 'id_plint');
    }
}
