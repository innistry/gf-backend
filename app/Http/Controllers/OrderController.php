<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Tariff;
use App\Services\UserService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * @return OrderCollection
     */
    public function index(): OrderCollection
    {
        return new OrderCollection(Order::query()->orderBy('id', 'desc')->get());
    }

    /**
     * @param Request $request
     * @return OrderResource
     */
    public function store(Request $request): OrderResource
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string',

            'tariff_id' => 'required|integer|exists:tariffs,id',

            'started_at' => 'required|date',

            'location_id' => 'required|integer|exists:locations,id',
            'address' => 'required|string',
        ]);

        $userService = new UserService();

        $order = \DB::transaction(function () use ($request, $userService) {

            $user = $userService->getOrCreate($request->phone, $request->name);

            $orderService = new OrderService($user);

            return $orderService->saveOrder(
                Tariff::findOrFail($request->tariff_id),
                $request->location_id,
                $request->address,
                Carbon::parse($request->started_at));
        });

        return OrderResource::make($order)
            ->withCode(201)
            ->withLocation(route('orders.show', $order->id));
    }

    /**
     * @param string $id
     * @return OrderResource
     */
    public function show(string $id): OrderResource
    {
        return OrderResource::make(Order::findOrFail($id));
    }

    /**
     *
     */
    public function update()
    {
        abort(404);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        \DB::transaction(function () use ($order) {
            $order->delete();
        });

        return response()->noContent();
    }
}
