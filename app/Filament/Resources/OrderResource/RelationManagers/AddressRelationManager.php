<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                TextInput::make('city')
                    ->required()
                    ->maxLength(255),

                TextInput::make('state')
                    ->required()
                    ->maxLength(255),

                TextInput::make('zip_code')
                    ->required()
                    ->numeric()
                    ->maxLength(10),

                TextArea::make('street_address') // Fixed the typo
                    ->required()
                    ->columnSpanFull(),
                    
                TextArea::make('full_address') // Fixed the typo
                ->required()
                ->columnSpanFull() // Fixed the method name
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address') // Fixed the typo
            ->columns([

                TextColumn::make('fullname')
               ->label('Full Name'),

               TextColumn::make('phone'),
               TextColumn::make('city'),
               TextColumn::make('state'),
               TextColumn::make('zip_code'),
               TextColumn::make('street_address'),
               TextColumn::make('full_address')


            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
