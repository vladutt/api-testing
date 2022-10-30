<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndpointAuth extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'endpoint_auth';

    public function authParams() : Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true)
        );
    }
}
