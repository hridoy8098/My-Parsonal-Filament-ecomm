<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array 
    {
        return [
            OrderStats::class,
        ];
    }
 
    public function getTabs(): array 
{
    return [
        null => Tab::make('All'),
        Tab::make('New')->query(fn ($query) => $query->where('status', 'new')),
        Tab::make('Processing')->query(fn ($query) => $query->where('status', 'processing')),
        Tab::make('Shipped')->query(fn ($query) => $query->where('status', 'shipped')),
        Tab::make('Delivered')->query(fn ($query) => $query->where('status', 'delivered')),
        Tab::make('Cancelled')->query(fn ($query) => $query->where('status', 'cancelled'))
    ];
}
}
