<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes,  HasFactory;

    protected $fillable = [
        'name',
        'company',
        'external_reference',
    ];

    /**
     * @return Collection
     */
    public function users() {
        return $this->hasMany(User::class);
    }

    /**
     * @return Collection
     */
    public function prices() {
        return $this->hasMany(Price::class);
    }
}
