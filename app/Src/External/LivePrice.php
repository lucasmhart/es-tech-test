<?php

namespace App\Src\External;

class LivePrice {
    /**
     * @var array
     */
    private $prices;

    public function __construct()
    {
        $this->getLivePrices();
    }

    /**
     * @param Account $account
     * @param array $products
     * @return array
     */
    public function getProductPrices($account, $products) {
        $result = [];

        foreach($this->prices as $price) {
            if(!$this->isValidLivePrice($price, $account, $products)) {
                continue;
            }

            $result[$price['sku']] = $price['price'];
        }
        return $result;
    }

    private function getLivePrices() {
        $json = file_get_contents(resource_path('api_source/live_prices.json'));
        $this->prices = json_decode($json, true);
    }

    /**
     * @param array $price
     * @param Account $account
     * @param array $products
     * @return bool
     */
    private function isValidLivePrice($price, $account, $products) {
        $skus = array_map(function ($product) {return $product['sku'];}, $products);

        if(isset($price['account']) && ! $account) {
            return false;
        }

        if(isset($price['account']) && ($account->external_reference != $price['account'])) {
            return false;
        }

        if(! isset($price['account']) && $account) {
            return false;
        }

        return in_array($price['sku'], $skus);
    }
}
