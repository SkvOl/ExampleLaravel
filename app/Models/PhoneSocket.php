<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\Room;
use App\Models\PhoneSocketExecution;
use App\Models\PhonePlintPortSocketPivot;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_socket', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneSocket extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_socket';

    protected $dateFormat = 'U';

    public static $model_id = 69;

    protected $allowedOrderKeys = [
        'id',
        'name',
        'id_room'
    ];

    protected $fillable = [
        'name',
        'id_room'
    ];

    const DELETED_AT = 'is_deleted';

    public function socketExecution()
    {
        return $this->hasMany(PhoneSocketExecution::class, 'id', 'id_socket');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id');
    }
    
    public function plintPortSocket()
    {
        return $this->hasMany(PhonePlintPortSocketPivot::class, 'id', 'id_socket');
    }
}
