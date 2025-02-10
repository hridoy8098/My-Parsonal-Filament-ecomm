<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order; 
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $averagePrice = Order::query()->avg('grand_total');
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('status', 'Processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('status', 'Shipped')->count()),
            Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'), 'bdt')),
            //Stat::make('Average Price', Number::currency($averagePrice ?? 0, 'bdt')),
        ];
    }
}
