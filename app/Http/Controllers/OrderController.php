<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index(): OrderCollection
    {
        return new OrderCollection(Order::query()->orderBy('id', 'desc')->get());
    }

    public function store(Request $request): OrderResource
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string',
            'tariff_id' => 'required|integer',
        ]);

        $order = \DB::transaction(function () use ($request) {
            $phone = $request->phone; // TODO нормализация телефона

            $user = User::where('phone', $phone)->first();

            if (!$user) {
                $user = User::create([
                    'phone' => $phone,
                    'name' => $request->name,
                ]);
            } elseif ($user->name != $request->name) {
                $user->update([
                    'name' => $request->name,
                ]);
            }

            $tariff = \App\Models\Tariff::find($request->tariff_id);

            $started_at = now()->addDay(random_int(0, 20));

            return Order::create([
                'user_id' => $user->id,
                'tariff_id' => $tariff->id,
                'started_at' => $started_at,
                'ended_at' => $started_at->addDay(7),
            ]);
        });

        return OrderResource::make($order)
            ->withCode(201)
            ->withLocation(route('orders.show', $order->id));
    }

    public function show(string $id): OrderResource
    {
        return OrderResource::make(Order::findOrFail($id));
    }

    public function update()
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        \DB::transaction(function () use ($order) {
            $order->delete();
        });

        return response()->noContent();
    }
}
