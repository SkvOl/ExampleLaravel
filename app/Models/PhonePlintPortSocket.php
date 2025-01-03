<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhoneSocket;
use App\Models\PhonePlintPort;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

class PhonePlintPortSocket extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;
    protected $table = 'lk.phone_plint_port_socket';
    protected $dateFormat = 'U';
    protected $fillable = [
        'id',
        'id_port_plint',
        'id_socket'
    ];
    const DELETED_AT = 'is_deleted';
    public function plintSocket()//
    {
        return $this->belongsTo(PhoneSocket::class, 'id');
    }
    public function plintPort()//
    {
        return $this->belongsTo(PhonePlintPort::class,'id');
    }
}
