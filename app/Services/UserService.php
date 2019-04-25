<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @param string $phone
     * @param string $name
     * @return User
     */
    public function getOrCreate(string $phone, string $name): User
    {
        return \DB::transaction(function () use ($phone, $name) {
            \DB::raw('LOCK TABLE users IN EXCLUSIVE MODE');

            $user = User::where('phone', $phone)->first();

            if (!$user) {
                $user = User::create([
                    'phone' => $phone,
                    'name' => $name,
                ]);
            } elseif ($user->name != $name) {
                $user->update([
                    'name' => $name,
                ]);
            }

            return $user;
        });
    }
}
