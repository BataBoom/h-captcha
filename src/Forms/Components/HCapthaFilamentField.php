<?php

namespace BataBoom\Captcha\Forms\Components;

use Filament\Forms\Components\Field;

class HCapthaFilamentField extends Field
{
    protected string $view = 'h-captcha::forms.components.h-captcha';
    
    public function setUp(): void
    {
        parent::setUp();
        $this->rules('required|captcha');
        $this->dehydrated(false);
        $this->label('');
    }

    public function callBeforeStateDehydrated(): static
    {
        parent::callBeforeStateDehydrated();

        if (method_exists($this->getLivewire(), 'dispatchFormEvent')) {
            $this->getLivewire()->dispatchFormEvent('resetCaptcha');
        } else {
            $this->getLivewire()->emit('resetCaptcha');
        }

        return $this;
    }
}