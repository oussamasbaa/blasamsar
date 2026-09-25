<?php

namespace App\Filament\Resources\Workers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class WorkerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->tel()
                                ->maxLength(20),
                            TextInput::make('position')
                                ->maxLength(255),
                        ]),
                        FileUpload::make('profile_picture')
                            ->image()
                            ->avatar()
                            ->directory('worker-photos')
                            ->columnSpanFull(),
                    ]),

                Section::make('Organization')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('company_id')
                                ->relationship('company', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')->required(),
                                    TextInput::make('address'),
                                ]),
                            Select::make('branch_id')
                                ->relationship('branch', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('department')
                                ->options([
                                    'engineering' => 'Engineering',
                                    'sales' => 'Sales',
                                    'hr' => 'Human Resources',
                                    'finance' => 'Finance',
                                    'operations' => 'Operations',
                                    'marketing' => 'Marketing',
                                    'management' => 'Management',
                                    'other' => 'Other',
                                ])
                                ->searchable(),
                        ]),
                    ]),

                Section::make('Work Schedule & Status')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('shift_id')
                                ->relationship('shift', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('status')
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                    'on_leave' => 'On Leave',
                                    'terminated' => 'Terminated',
                                ])
                                ->default('active')
                                ->required(),
                            TextInput::make('leave_balance_vacation')
                                ->label('Vacation Days')
                                ->numeric()
                                ->default(20),
                        ]),
                    ]),
            ]);
    }
}
