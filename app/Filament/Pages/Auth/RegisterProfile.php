<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Auth\Register;

class RegisterProfile extends Register
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent()->validationMessages([
                    'required' => 'A senha é obrigatória.',
                    'min' => 'A senha deve ter no mínimo 8 caracteres.',
                    'same' => 'As senhas não coincidem.',
                ]),
                $this->getPasswordConfirmationFormComponent(),
                TextInput::make('profile_link')->label('Link do Perfil')
                    ->readOnly()
                    ->inlineLabel(false)
                    ->maxLength(255),
            ]);
    }
}
