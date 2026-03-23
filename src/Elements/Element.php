<?php

namespace TypiCMS\Form\Elements;

abstract class Element implements \Stringable
{
    protected array $attributes = [];

    protected function setAttribute(string $attribute, mixed $value = null): void
    {
        if (is_null($value)) {
            return;
        }

        $this->attributes[$attribute] = $value;
    }

    protected function removeAttribute(string $attribute): void
    {
        unset($this->attributes[$attribute]);
    }

    public function getAttribute(string $attribute): string
    {
        return $this->attributes[$attribute];
    }

    public function data(array|string $attribute, mixed $value = null): self
    {
        if (is_array($attribute)) {
            foreach ($attribute as $key => $val) {
                $this->setAttribute('data-'.$key, $val);
            }
        } else {
            $this->setAttribute('data-'.$attribute, $value);
        }

        return $this;
    }

    public function attribute(string $attribute, mixed $value): self
    {
        $this->setAttribute($attribute, $value);

        return $this;
    }

    public function clear(string $attribute): self
    {
        if (! isset($this->attributes[$attribute])) {
            return $this;
        }

        $this->removeAttribute($attribute);

        return $this;
    }

    public function addClass(string $class): self
    {
        if (isset($this->attributes['class'])) {
            $class = $this->attributes['class'].' '.$class;
        }

        $this->setAttribute('class', $class);

        return $this;
    }

    public function removeClass(string $class): self
    {
        if (! isset($this->attributes['class'])) {
            return $this;
        }

        $class = trim(str_replace($class, '', $this->attributes['class']));
        if ($class === '') {
            $this->removeAttribute('class');

            return $this;
        }

        $this->setAttribute('class', $class);

        return $this;
    }

    public function id(string $id): self
    {
        $this->setId($id);

        return $this;
    }

    protected function setId(string $id): void
    {
        $this->setAttribute('id', $id);
    }

    abstract public function render(): string;

    public function __toString(): string
    {
        return $this->render();
    }

    protected function renderAttributes(): string
    {
        [$attributes, $values] = $this->splitKeysAndValues($this->attributes);

        return implode('', array_map(fn (string $attribute, ?string $value): string => sprintf(' %s="%s"', $attribute, $this->escape($value)), $attributes, $values));
    }

    protected function splitKeysAndValues(array $array): array
    {
        // Disgusting crap because people might have passed a collection
        $keys = [];
        $values = [];

        foreach ($array as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        return [$keys, $values];
    }

    protected function setBooleanAttribute(string $attribute, bool $value): void
    {
        if ($value) {
            $this->setAttribute($attribute, $attribute);
        } else {
            $this->removeAttribute($attribute);
        }
    }

    protected function escape(?string $value): string
    {
        if (is_null($value)) {
            $value = '';
        }

        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }

    public function __call(string $method, array $params): self
    {
        $params = $params !== [] ? $params : [$method];
        $params = array_merge([$method], $params);
        $this->attribute(...$params);

        return $this;
    }
}
