<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes,  HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
    ];

    /**
     * @return Collection
     */
    public function prices() {
        return $this->hasMany(Price::class);
    }

    /**
     * @param Account $account
     * @return float
     */
    public function getPrice($account) {
        $account_id = $account ? $account->id : null;
        $price = $this->prices->where('account_id', $account_id)->min('value');
        return $price ? $price : $this->price;
    }
}
