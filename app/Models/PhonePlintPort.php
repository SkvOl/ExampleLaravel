<?php

namespace App\Models;

use App\Http\sources\ConfigurableTrait;
use App\Models\PhonePlint;
use App\Models\PhonePlintPortSocketPivot;
use App\Models\PhoneGatewayPlintPivot;
use App\Http\sources\SoftDeleteFlagTrait;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Attributes as OAT;

#[OAT\Parameter(name: 'id', parameter: 'id_plintport', description: 'id номера', in: 'query', required: true, deprecated: false, allowEmptyValue: false)]
class PhonePlintPort extends Model
{
    use ConfigurableTrait;
    use SoftDeleteFlagTrait;

    protected $table = 'lk.phone_plint_port';

    protected $dateFormat = 'U';

    public static $model_id = 67;

    protected $allowedOrderKeys = [
        'id',
        'id_plint',
        'name'
    ];

    protected $fillable = [
        'id_plint',
        'name'
    ];

    const DELETED_AT = 'is_deleted';

    public function plint()
    {
        return $this->belongsTo(PhonePlint::class, 'id_plint', 'id');
    }

    public function plintPortSocket()
    {
        return $this->hasMany(PhonePlintPortSocketPivot::class, 'id', 'id_port_plint');
    }
    
    public function gatewayPlint()
    {
        return $this->hasMany(PhoneGatewayPlintPivot::class, 'id', 'id_port_plint');
    }
}