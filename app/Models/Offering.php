<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Offering extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        "property_id",
        "type",
        "minimum_area",
        "maximum_area",
        "image",
    ];

    public static function booted()
    {
        self::updated(function (Offering $record): void {
            $directory = "properties/offerings";

            $key = "image";
            if ($record->wasChanged($key)) {
                Storage::disk('public')->delete("$directory/" . $record->getOriginal($key));
            }
        });

        self::deleted(function (Offering $record): void {
            $directory = "properties/offerings";

            $key = "image";
            Storage::disk('public')->delete("$directory/" . $record[$key]);
        });
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
