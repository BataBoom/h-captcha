<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use BataBoom\Captcha\Forms\Components\HCapthaFilamentField;

/*
 * To integrate this functionality into your Filament Service Provider, 
 * you can configure the panel as follows:
 *
 * return $panel
 *     ->default()
 *     ->id('admin')
 *     ->path('admin')
 *     ->login(AdminLoginExample::class);
 */
class AdminLoginExample extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        HCapthaFilamentField::make('captcha'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
