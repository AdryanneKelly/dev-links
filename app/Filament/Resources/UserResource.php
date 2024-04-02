<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuários';

    public static function form(Form $form): Form
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
                                    ->disk('public')
                                    ->alignCenter()
                                    ->imageEditor()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('name')->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('E-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nickname')
                                    ->alphaDash()
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
                                Forms\Components\TextInput::make('password')->label('Senha')
                                    ->password()
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255),
                                Select::make('user_type')
                                    ->label('Tipo de Usuário')
                                    ->options([
                                        'admin' => 'Administrador',
                                        'user' => 'Usuário',
                                    ]),
                                Forms\Components\TextInput::make('profile_link')->label('Link do Perfil')
                                    ->readOnly()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('occupation')->label('Ocupação/Profissão')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('bio')
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
                                        Repeater::make('links')->relationship()
                                            ->columnSpanFull()
                                            ->schema([
                                                Forms\Components\TextInput::make('title')->label('Título')->required(),
                                                Forms\Components\TextInput::make('url')->label('URL')->required(),
                                            ])->grid(2)
                                    ]),
                                Fieldset::make('Links Inferiores')
                                    ->schema([
                                        Repeater::make('bottomLinks')->relationship()
                                            ->columnSpanFull()
                                            ->schema([
                                                Forms\Components\TextInput::make('title')->label('Título')->required(),
                                                Forms\Components\TextInput::make('url')->label('URL')->required(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nickname')->label('Nickname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('occupation')->label('Ocupação/Profissão')
                    ->searchable(),
                ImageColumn::make('avatar')->circular()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
