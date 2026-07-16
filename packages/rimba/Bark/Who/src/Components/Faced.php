<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

class FaceVerification extends Field
{
    protected string $view = 'filament.forms.components.face-verification';


    protected array $configuration = [];


    /*
    |--------------------------------------------------------------------------
    | Configuration Helper
    |--------------------------------------------------------------------------
    */

    protected function setConfig(
        string $key,
        mixed $value
    ): static {

        $this->configuration[$key] = $value;

        return $this;
    }


    public function getConfiguration(): array
    {
        return $this->evaluate($this->configuration);
    }


    /*
    |--------------------------------------------------------------------------
    | Fluent API
    |--------------------------------------------------------------------------
    */


    public function staffNumber(
        string|Closure $value
    ): static {

        return $this->setConfig(
            'staffNumber',
            $value
        );

    }


    public function threshold(
        float $value = 0.5
    ): static {

        return $this->setConfig(
            'threshold',
            $value
        );

    }


    public function camera(
        array $value
    ): static {

        return $this->setConfig(
            'camera',
            $value
        );

    }


    public function autoStart(
        bool $value = true
    ): static {

        return $this->setConfig(
            'autoStart',
            $value
        );

    }


    public function onMatched(
        string $event
    ): static {

        return $this->setConfig(
            'event',
            $event
        );

    }
}