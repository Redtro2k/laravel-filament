<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PurchaseRequisition extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function requester(){
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function preparer(){
        return $this->belongsTo(User::class, 'prepared_by_id');
    }
    public function checker(){
        return $this->belongsTo(User::class, 'checked_by_id');
    }
    public function mansor(){
        return $this->belongsTo(User::class, 'approved_by_id');
    }
    public function executive(){
        return $this->belongsTo(User::class, 'executed_by_id');
    }
}
