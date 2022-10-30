<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    use HasFactory;

    protected function createdAt() : Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y H:i')
        );
    }

    protected function updatedAt() : Attribute {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y H:i')
        );
    }

    protected function host() : Attribute {
        return Attribute::make(
            set: fn ($value) => url_parser($value),
        );
    }

    public function endpoints() {
        return $this->hasMany(Endpoint::class);
    }
}
