<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes,  HasFactory;

    protected $fillable = [
        'product_id',
        'account_id',
        'user_id',
        'quantity',
        'value',
    ];

    /**
     * @return Product
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return Account
     */
    public function account() {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
