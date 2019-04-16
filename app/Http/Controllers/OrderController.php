<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\User;
use App\Models\Tariff;
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

            'tariff_id' => 'required|integer|exists:tariffs,id',

            'started_at' => 'required|date',

            'location_id' => 'required|integer|exists:locations,id',
            'address' => 'required|string',
        ]);

        // TODO Вынести логику в какой-нибудь сервис

        $order = \DB::transaction(function () use ($request) {
            $phone = $request->phone; // TODO нормализация телефона

            \DB::raw('LOCK TABLE users');

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

            $tariff = Tariff::findOrFail($request->tariff_id);

            $started_at = Carbon::parse($request->started_at);

            return Order::create([
                'user_id' => $user->id,
                'tariff_id' => $tariff->id,
                'location_id' => $request->location_id,
                'address' => $request->address,
                'started_at' => $started_at,
                'ended_at' => $started_at->copy()->addDay($tariff->duration),
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
