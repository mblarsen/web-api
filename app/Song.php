<?php

namespace App;

use App\Casts\Money;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'bool',
        'is_digital' => 'bool',
        'is_taxable' => 'bool',
        'preview_start' => 'float',
        'price' => Money::class,
        'requires_shipping' => 'bool',
        'track_number' => 'int',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function flacFile()
    {
        return $this->belongsTo(FlacFile::class);
    }

    public function copiesSold()
    {
        return $this->morphMany(DigitalAsset::class, 'asset');
    }

    public function asset()
    {
        return $this->morphOne(DigitalAsset::class, 'asset');
    }
}
