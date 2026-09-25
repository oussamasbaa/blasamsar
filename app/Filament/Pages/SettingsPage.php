<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Paramètres';

    protected static ?string $title = 'Paramètres Système';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.settings';

    public ?int $stockAlertThreshold = 5;

    public ?string $smtpHost = 'smtp.mailtrap.io';

    public ?int $smtpPort = 587;

    public ?string $smtpUsername = '';

    public ?string $smtpPassword = '';

    public ?string $notificationEmail = 'admin@apexcar.com';

    protected static function settingsPath(): string
    {
        return storage_path('app/settings.json');
    }

    protected static function loadSettings(): array
    {
        $path = static::settingsPath();

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    public function mount(): void
    {
        $settings = static::loadSettings();

        $this->stockAlertThreshold = (int) ($settings['stock_alert_threshold'] ?? 5);
        $this->smtpHost = $settings['smtp_host'] ?? 'smtp.mailtrap.io';
        $this->smtpPort = (int) ($settings['smtp_port'] ?? 587);
        $this->smtpUsername = $settings['smtp_username'] ?? '';
        $this->smtpPassword = $settings['smtp_password'] ?? '';
        $this->notificationEmail = $settings['notification_email'] ?? 'admin@apexcar.com';

        $this->form->fill([
            'stockAlertThreshold' => $this->stockAlertThreshold,
            'smtpHost' => $this->smtpHost,
            'smtpPort' => $this->smtpPort,
            'smtpUsername' => $this->smtpUsername,
            'smtpPassword' => $this->smtpPassword,
            'notificationEmail' => $this->notificationEmail,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Alertes de Stock')
                ->schema([
                    Forms\Components\TextInput::make('stockAlertThreshold')
                        ->label('Seuil d\'alerte stock')
                        ->numeric()
                        ->default(5)
                        ->helperText('Nombre minimum de véhicules avant alerte')
                        ->required(),
                ]),
            Section::make('Configuration Email')
                ->schema([
                    Forms\Components\TextInput::make('smtpHost')
                        ->label('Serveur SMTP')
                        ->default('smtp.mailtrap.io'),
                    Forms\Components\TextInput::make('smtpPort')
                        ->label('Port SMTP')
                        ->numeric()
                        ->default(587),
                    Forms\Components\TextInput::make('smtpUsername')
                        ->label('Nom d\'utilisateur SMTP'),
                    Forms\Components\TextInput::make('smtpPassword')
                        ->label('Mot de passe SMTP')
                        ->password()
                        ->revealable(),
                    Forms\Components\TextInput::make('notificationEmail')
                        ->label('Email de notification')
                        ->email()
                        ->default('admin@apexcar.com'),
                ])->columns(2),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = [
            'stock_alert_threshold' => $data['stockAlertThreshold'],
            'smtp_host' => $data['smtpHost'],
            'smtp_port' => $data['smtpPort'],
            'smtp_username' => $data['smtpUsername'],
            'smtp_password' => $data['smtpPassword'],
            'notification_email' => $data['notificationEmail'],
        ];

        $path = static::settingsPath();

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT));

        Notification::make()
            ->title('Paramètres sauvegardés')
            ->success()
            ->send();
    }
}
