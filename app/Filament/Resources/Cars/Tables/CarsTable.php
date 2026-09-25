<?php

namespace App\Filament\Resources\Cars\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->limit(1)
                    ->action(
                        Action::make('editImage')
                            ->label('Modifier l\'image')
                            ->icon('heroicon-m-pencil-square')
                            ->form([
                                FileUpload::make('new_image')
                                    ->label('Nouvelle image')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('cars')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->required(),
                            ])
                            ->fillForm(function ($record): array {
                                return [
                                    'new_image' => $record->images[0] ?? null,
                                ];
                            })
                            ->action(function ($record, array $data): void {
                                $record->images = [$data['new_image']];
                                $record->save();

                                Notification::make()
                                    ->title('Image mise à jour avec succès')
                                    ->success()
                                    ->send();
                            })
                            ->modalSubmitActionLabel('Sauvegarder')
                            ->modalCancelActionLabel('Annuler'),
                    ),
                TextColumn::make('carModel.brand.name')
                    ->label('Marque')
                    ->sortable(),
                TextColumn::make('carModel.name')
                    ->label('Modèle')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('year')
                    ->label('Année')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Prix')
                    ->money('MAD')
                    ->sortable()
                    ->color('danger')
                    ->weight('bold'),
                TextColumn::make('mileage')
                    ->label('Kilométrage')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),
                TextColumn::make('fuel_type')
                    ->label('Carburant')
                    ->searchable(),
                TextColumn::make('transmission')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Statut')
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'sold' => 'danger',
                        'reserved' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Couleur')
                    ->searchable(),
                TextColumn::make('condition')
                    ->badge()
                    ->color('info')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
