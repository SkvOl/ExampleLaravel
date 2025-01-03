<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\UserExecution;
use App\Models\PhoneSocket;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_socketexecution', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhoneSocketExecution extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_socket_execution';

    protected $dateFormat = 'U';

    public static $model_id = 70;

    protected $allowedOrderKeys = [
        'id',
        'id_socket',
        'id_execution',
        'text'
    ];

    protected $fillable = [
        'id_socket',
        'id_execution',
        'text'
    ];

    const DELETED_AT = 'is_deleted';

    public function socket()
    {
        return $this->belongsTo(PhoneSocket::class, 'id_socket', 'id');
    }
    
    public function userExecution()
    {
        return $this->belongsTo(UserExecution::class, 'id_execution', 'id');
    }
}
