<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;




    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')

            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                    TextColumn::make('user.name')
                    ->searchable(),

                    TextColumn::make('grand_total')
                    ->money('BDT'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary', // Fallback color
                    })
                    ->icon(fn(string $state): string => match($state) {
                        'new' => 'heroicon-o-star',
                        'processing' => 'heroicon-o-clock',
                        'shipped' => 'heroicon-o-truck',
                        'delivered' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => '', // Fallback icon
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->sortable()
                    ->badge()
                    ->searchable(),

                    TextColumn::make('created_at')
                    ->label('Order date')
                    ->dateTime()
            
            ])

            ->actions([
                Action::make('View Order')
                ->url(fn ($record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),

            ]);

            }
}
