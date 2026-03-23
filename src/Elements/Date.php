<?php

namespace TypiCMS\Form\Elements;

use DateTime;

class Date extends Text
{
    protected array $attributes = [
        'type' => 'date',
    ];

    public function value(mixed $value): Input
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        return parent::value($value);
    }

    public function defaultValue(mixed $value): self
    {
        if (! $this->hasValue()) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }

            $this->setValue($value);
        }

        return $this;
    }
}
