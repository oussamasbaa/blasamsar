<?php

namespace App\Filament\Resources\Reservations;

use App\Filament\Resources\Reservations\Pages\CreateReservation;
use App\Filament\Resources\Reservations\Pages\EditReservation;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Models\Reservation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Actions;
use Filament\Tables;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Réservations';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Components\Section::make('Détails Réservation')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Client')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('car_id')
                        ->label('Véhicule')
                        ->relationship('car', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->carModel->brand->name . ' ' . $record->carModel->name . ' (' . $record->year . ')')
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columns(2),
            Components\Section::make('Dates & Statut')
                ->schema([
                    Forms\Components\DatePicker::make('reservation_date')
                        ->label('Date de réservation')
                        ->required(),
                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('Date d\'expiration')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Statut')
                        ->options([
                            'pending' => 'En attente',
                            'confirmed' => 'Confirmée',
                            'cancelled' => 'Annulée',
                            'completed' => 'Terminée',
                        ])
                        ->required()
                        ->default('pending'),
                ])->columns(3),
            Components\Section::make('Informations Acheteur')
                ->schema([
                    Forms\Components\Textarea::make('buyer_info')
                        ->label('Infos acheteur')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car')
                    ->label('Véhicule')
                    ->getStateUsing(fn ($record) => $record->car->carModel->brand->name . ' ' . $record->car->carModel->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('reservation_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmée',
                        'cancelled' => 'Annulée',
                        'completed' => 'Terminée',
                    }),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expire le')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmée',
                        'cancelled' => 'Annulée',
                        'completed' => 'Terminée',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReservations::route('/'),
            'create' => CreateReservation::route('/create'),
            'edit' => EditReservation::route('/{record}/edit'),
        ];
    }
}
