<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Appointment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'property_id',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'date',
        'time',
        'message',
        'status',
    ];

    protected $attributes = [
        'status' => 'Pending'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
