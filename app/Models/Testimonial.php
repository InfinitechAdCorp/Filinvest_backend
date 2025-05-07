<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Testimonial extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'message',
        'isPublished',
    ];

    protected $attributes = [
        'isPublished' => 0,
    ];
}
