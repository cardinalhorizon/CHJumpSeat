<?php

namespace Modules\CHJumpSeat\Models;

use App\Contracts\Model;
use App\Models\Airport;
use App\Models\User;

/**
 * Class CHJumpseatRequest
 * @package Modules\CHJumpSeat\Models
 */
class CHJumpseatRequest extends Model
{
    public $table = 'ch_jumpseat_requests';
    protected $fillable = ['user_id', 'airport_id', 'request_reason', 'type'];

    public $casts = [
        'status' => 'integer',
        'type'   => 'integer'
    ];
    public function airport() {
        return $this->belongsTo(Airport::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function approver() {
        return $this->belongsTo(User::class);
    }
}
