<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PurchaseRequisition extends Model
{
    //
    protected $guarded = [];

    public function requester(){
        return $this->belongsTo(User::class);
    }
}
