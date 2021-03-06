<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes,  HasFactory;

    protected $fillable = [
        'name',
        'email',
        'account_id',
        'external_reference',
    ];

    /**
     * @return Account
     */
    public function account() {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return Collection
     */
    public function prices() {
        return $this->hasMany(Price::class);
    }
}
