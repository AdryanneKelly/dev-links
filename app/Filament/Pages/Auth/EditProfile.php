<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Configurações de Usuário')
                    ->tabs([
                        Tab::make('Informações de Perfil')
                            ->schema([
                                FileUpload::make('avatar')
                                    ->directory('avatars/' . auth()->id())
                                    ->image()
                                    ->required()
                                    ->avatar()
                                    ->inlineLabel(false)
                                    ->disk('public')
                                    ->alignCenter()
                                    ->imageEditor()
                                    ->columnSpanFull(),
                                $this->getNameFormComponent()->label('Nome')
                                    ->required()
                                    ->inlineLabel(false)
                                    ->maxLength(255),
                                $this->getEmailFormComponent()
                                    ->label('E-mail')
                                    ->email()
                                    ->inlineLabel(false)
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('nickname')
                                    ->alphaDash()
                                    ->inlineLabel(false)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Esse nick será usado para criar o link do seu perfil e não deve conter espaços ou acentos. Exemplo: "joao_silva"')
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $set('profile_link', url('/') . '/dev/' . $state);
                                    })->live()
                                    ->validationMessages([
                                        'unique' => 'Este nick já está em uso.',
                                        'alpha_dash' => 'O nickname não pode conter espaços, caracteres especiais ou acentos.',
                                        'required' => 'O nickname é obrigatório.',
                                    ])
                                    ->maxLength(255),
                                $this->getPasswordFormComponent()->label('Senha')
                                    ->password()
                                    ->inlineLabel(false)
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255),
                                $this->getPasswordConfirmationFormComponent(),
                                TextInput::make('profile_link')->label('Link do Perfil')
                                    ->readOnly()
                                    ->inlineLabel(false)
                                    ->maxLength(255),
                                TextInput::make('occupation')->label('Ocupação/Profissão')->inlineLabel(false)
                                    ->maxLength(255),
                                Textarea::make('bio')->inlineLabel(false)
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Configurações de Cores')
                            ->schema([
                                ColorPicker::make('primary_color')->label('Cor Primária')->required(),
                                ColorPicker::make('secondary_color')->label('Cor Secundária')->required(),
                                ColorPicker::make('tertiary_color')->label('Cor Terciária')->required(),
                                ColorPicker::make('text_color')->label('Cor do Texto')->required(),
                                ColorPicker::make('border_color')->label('Cor da Borda')->required(),
                                ColorPicker::make('menu_color')->label('Cor do botão de link')->required()->rgba(),
                            ])->columns(2),
                        Tab::make('Links do Perfil')
                            ->schema([
                                Fieldset::make('Links')
                                    ->schema([
                                        Repeater::make('links')->relationship()->inlineLabel(false)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('title')->label('Título')->required(),
                                                TextInput::make('url')->label('URL')->required(),
                                            ])->grid(2)
                                    ]),
                                Fieldset::make('Links Inferiores')
                                    ->schema([
                                        Repeater::make('bottomLinks')->relationship()->inlineLabel(false)
                                            ->columnSpanFull()
                                            ->schema([
                                                TextInput::make('title')->label('Título')->required(),
                                                TextInput::make('url')->label('URL')->required(),
                                                FileUpload::make('icon')->label('Ícone')
                                                    ->avatar()
                                                    ->required()
                                                    ->hint('Escolha imagens com fundo transparente para melhor visualização.')
                                                    ->image()
                                                    ->disk('public')
                                                    ->alignCenter()
                                                    ->directory('icons/' . auth()->id()),
                                            ])->grid(2)
                                    ]),
                            ]),
                    ])->columnSpanFull(),





            ]);
    }
}
