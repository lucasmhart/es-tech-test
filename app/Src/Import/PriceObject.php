<?php

namespace App\Src\Import;

use App\Models\Account;
use App\Models\Product;
use App\Models\User;

class PriceObject {
    /**
     * @var int
     */
    private $product_id;

    /**
     * @var int
     */
    private $account_id;

    /**
     * @var int
     */
    private $user_id;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $value;

    /**
     * @param array $importData
     */
    public function __construct($importData)
    {
        $this->setProductId($importData[0]);
        $this->setAccountId($importData[1]);
        $this->setUserId($importData[2]);
        $this->quantity = $importData[3];
        $this->value = $importData[4];
    }

    /**
     * @param string $sku
     */
    private function setProductId($sku)
    {
        $product = Product::where('sku', $sku)->first();

        $this->product_id = $product ? $product->id : null;
    }

    /**
     * @param string $ref
     */
    private function setUserId($ref)
    {
        $user = User::where('external_reference', $ref)->first();

        $this->user_id = $user ? $user->id : null;
    }

    /**
     * @param string $ref
     */
    private function setAccountId($ref)
    {
        $account = Account::where('external_reference', $ref)->first();

        $this->account_id = $account ? $account->id : null;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (!empty($this->product_id) &&
            is_numeric($this->quantity) &&
            is_numeric($this->value));
    }

    /**
     * @return array|null
     */
    public function toArray() {
        if(!$this->isValid()) {
            return;
        }

        return [
            'product_id' => $this->product_id,
            'account_id' => $this->account_id,
            'user_id'    => $this->user_id,
            'quantity'   => $this->quantity,
            'value'      => $this->value,
        ];
    }
}
