<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use App\Http\Resources\TariffCollection;

class TariffController extends Controller
{
    public function index(): TariffCollection
    {
        return new TariffCollection(Tariff::all());
    }
}
