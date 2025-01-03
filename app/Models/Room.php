<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Http\sources\SoftDeleteFlagTrait;
use App\Models\User;
use App\Models\RoomType;
use App\Models\PhoneGateway;
use App\Models\PhoneSocket;
use App\Models\PhonePlint;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_room', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class Room extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.room';

    protected $dateFormat = 'U';

    public static $model_id = 71;

    protected $allowedOrderKeys = [
        'id',
        'name',
        'description',
        'places',
        'places_work',
        'floor',
        'build',
        'serial_number',
        'id_type',
        'number',
        'id_dep',
        'owner_id',
        'owner_name',
        'is_workshop',
        'is_show'
    ];

    protected $fillable = [
        'name',
        'description',
        'places',
        'places_work',
        'floor',
        'build',
        'serial_number',
        'id_type',
        'number',
        'id_dep',
        'owner_id',
        'owner_name',
        'is_workshop',
        'is_show'
    ];

    const DELETED_AT = 'is_deleted';

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'id');
    }

    public function department()
    {
        return $this->belongsTo(RoomType::class, 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function gateway()
    {
        return $this->hasMany(PhoneGateway::class, 'id_room');
    }

    public function socket()
    {
        return $this->hasMany(PhoneSocket::class, 'id_room');
    }
    
    public function plint()
    {
        return $this->hasMany(PhonePlint::class, 'id_room');
    }
}
