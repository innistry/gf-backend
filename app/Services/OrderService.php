<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Tariff;
use Carbon\CarbonInterface;

class OrderService
{
    /**
     * @var User
     */
    private $user;

    /**
     * OrderService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Tariff $tariff
     * @param int $locationId
     * @param string $address
     * @param CarbonInterface $startedAt
     * @return Order
     */
    public function saveOrder(Tariff $tariff, int $locationId, string $address, CarbonInterface $startedAt): Order
    {
        return Order::create([
            'user_id' => $this->user->id,
            'tariff_id' => $tariff->id,
            'location_id' => $locationId,
            'address' => $address,
            'started_at' => $startedAt,
            'ended_at' => $startedAt->copy()->addDays($tariff->duration),
        ]);
    }
}
