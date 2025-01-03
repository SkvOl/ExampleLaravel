<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Http\sources\SoftDeleteFlagTrait;
use App\Models\Room;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_roomtype', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class RoomType extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.room_type';

    protected $fillable = [
        'id',
        'id_parent',
        'name'
    ];

    const DELETED_AT = 'is_deleted';

    public function rooms()
    {
        return $this->hasMany(Room::class, 'id_type');
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class, 'id_parent');
    }
    
    public function listRoomTypes()
    {
        return $this->belongsTo(RoomType::class, 'id');
    }
}
