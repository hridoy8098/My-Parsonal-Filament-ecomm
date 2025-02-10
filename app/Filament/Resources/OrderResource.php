<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager; // Import the AddressRelationManager
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Utilities\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 18 ? 'success' : 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Information')->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cod' => 'Cash on Delivery'
                        ])
                        ->required(),

                    Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed'
                        ])
                        ->default('pending')
                        ->required(),

                    ToggleButtons::make('status')
                        ->label('Status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled'
                        ])
                        ->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'shipped' => 'success',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ])
                        ->icons([
                            'new' => 'heroicon-m-sparkles',
                            'processing' => 'heroicon-m-arrow-path',
                            'shipped' => 'heroicon-m-truck',
                            'delivered' => 'heroicon-m-check-badge',
                            'cancelled' => 'heroicon-m-x-circle'
                        ]),
                    Select::make('currency')
                        ->options([
                            'bdt' => 'BDT',
                            'usd' => 'USD',
                            'eur' => 'EUR',
                        ])
                        ->default('BDT')
                        ->required(),

                    Select::make('shipping_method')
                        ->options([
                            'fedex' => 'FedEx',
                            'ups' => 'UPS',
                            'dhl' => 'DHL',
                            'usps' => 'USPS'
                        ])
                        ->required(),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                ]),

                Section::make('Order Items')->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->schema([

                            Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->afterStateUpdated(fn ($state, $set) => $set('unit_amount', Product::find($state)->price ?? 0))
                            ->afterStateUpdated(fn ($state, $set) => $set('total_amount', Product::find($state)->price ?? 0)),
                        

                              

                            TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $set('total_amount', $state * $get('unit_amount'))),

                               

                            TextInput::make('unit_amount')
                                
                                ->numeric()
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                
                                ->numeric()
                                ->required()
                               ->dehydrated()
                                ->columnSpan(5),
                        ]),
                ]),

                Placeholder::make('grand_total_placeholder')
                ->label('Grand Total')
                ->content(function (Get $get, Set $set) {
                    $total = 0;
                    $repeaters = $get('items');
            
                    if ($repeaters && is_array($repeaters)) {
                        foreach ($repeaters as $repeater) {
                            $total += $repeater['total_amount'] ?? 0;
                        }
                    }
            
                    $set('grand_total', $total);
                    return 'BDT ' . number_format($total, 2); 
                    }),

                Hidden::make('grand_total')
                    ->default(0)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('BDT'),

                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled'
                    ])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->datetime()
                    ->sortable()
                    ->toggleable(true),

                    TextColumn::make('updated_at')
                    ->datetime()
                    ->sortable()
                    ->toggleable(true),
            ])
            ->filters([
                // Define your table filters here
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
