<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPriceRequest;
use App\Models\Account;
use App\Models\Product;
use App\Src\External\LivePrice;

class PriceController extends Controller
{
    /**
     * @param GetPriceRequest $request
     * @return json
     */
    public function get(GetPriceRequest $request) {
        $products = Product::select('id', 'sku', 'price')
            ->whereIn('sku', $request->input('sku_list'))
            ->get();
        $account = Account::find($request->input('account_id'));

        $prices = $this->getProductsPrice($products, $account);

        return response()->json([
            'status' => 200,
            'result'  => $prices
        ]);
    }

    /**
     * Returns the price of the products according to the following rule:
     * 1 - Consult the live price;
     * 2 - For products that do not have a corresponding live price, search for
     *  the lowest price registered in the price table;
     * 3 - If none of the above conditions are met, the product value of the
     *  product table is returned.
     *
     * @param Collection $products
     * @param Account|null $account
     * @return array
     */
    private function getProductsPrice($products, $account){
        $livePrice = new LivePrice();
        $response = $livePrice->getProductPrices($account, $products->toArray());

        $test = Product::find(4520);
        $test->getPrice($account);

        foreach($products as $product) {
            if(in_array($product->sku, $response)){
                continue;
            }

            $price = $product->getPrice($account);

            $response[$product['sku']] = $price;
        }
        return $this->formatResponse($response);
    }

    /**
     * @param array $prices
     * @return array
     */
    private function formatResponse($prices) {
        $response = [];
        foreach($prices as $sku => $price) {
            $response[] = [
                'sku' => $sku,
                'price' => $price
            ];
        }

        return $response;
    }
}
