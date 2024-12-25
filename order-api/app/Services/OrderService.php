<?php

namespace App\Services;

use App\Validators\OrderValidator;
use App\Transformers\OrderTransformer;

class OrderService
{
    private $validator;
    private $transformer;

    public function __construct(
        OrderValidator $validator,
        OrderTransformer $transformer
    ) {
        $this->validator = $validator;
        $this->transformer = $transformer;
    }

    public function processOrder(array $orderData): array
    {
        $validationResult = $this->validator->validate($orderData);
        
        if (!$validationResult['isValid']) {
            return [
                'status' => 400,
                'errors' => $validationResult['errors']
            ];
        }

        return [
            'status' => 200,
            'data' => $this->transformer->transform($orderData)
        ];
    }
}