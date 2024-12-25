<?php

namespace App\Transformers;

class OrderTransformer
{
    private const USD_TO_TWD_RATE = 31;

    public function transform(array $order): array
    {
        $transformedOrder = $order;

        if ($order['currency'] === 'USD') {
            $transformedOrder['price'] *= self::USD_TO_TWD_RATE;
            $transformedOrder['currency'] = 'TWD';
        }

        return $transformedOrder;
    }
}