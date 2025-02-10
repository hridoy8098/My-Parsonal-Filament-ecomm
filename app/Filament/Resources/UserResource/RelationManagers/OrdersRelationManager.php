<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Forms;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;


class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

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
            ->filters([
                // Add any necessary filters here
            ])
            ->headerActions([
             
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('View Order')
                ->url(fn (order $record):string => OrderResource::getUrl('view',['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
