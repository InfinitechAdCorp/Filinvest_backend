<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        "name",
        "type",
        "location",
        "map",
        "minimum_price",
        "maximum_price",
        "minimum_area",
        "maximum_area",
        "status",
        "description",
        "isPublished",
        "isFeatured",
        "logo",
        "images",
        "amenities",
    ];

    protected $attributes = [
        "isPublished" => 0,
        "isFeatured" => 0,
    ];

    public static function booted()
    {
        self::updated(function (Property $record): void {
            $directory = "properties";

            $key = "logo";
            if ($record->wasChanged($key)) {
                Storage::disk('public')->delete("$directory/logos/" . $record->getOriginal($key));
            }

            $key  = "images";
            if ($record->wasChanged($key)) {
                $files = json_decode($record->getOriginal($key));
                foreach ($files as $file) {
                    Storage::disk('public')->delete("$directory/images/" . $file);
                }
            }
        });

        self::deleted(function (Property $record): void {
            $directory = "properties";

            $key = "logo";
            Storage::disk('public')->delete("$directory/logos/" . $record[$key]);

            $key  = "images";
            $files = json_decode($record[$key]);
            foreach ($files as $file) {
                Storage::disk('public')->delete("$directory/images/" . $file);
            }
        });
    }

    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }
}
