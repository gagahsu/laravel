<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $result = $this->orderService->processOrder($validatedData);
        return response()->json($result, $result['status']);
    }
}