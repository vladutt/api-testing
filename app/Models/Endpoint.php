<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function auth() {
        return $this->hasOne(EndpointAuth::class);
    }
    public function parameters() {
        return $this->hasOne(Parameter::class);
    }

    public function rules() : Attribute {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true)
        );
    }

    public function host() {
        return $this->hasOne(Host::class, 'id', 'host_id');
    }

    public function getFullEndpointAttribute() {
        return $this->host()->host.$this->endpoint;
    }

}
