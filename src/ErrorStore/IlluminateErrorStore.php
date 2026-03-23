<?php

namespace TypiCMS\Form\ErrorStore;

use Illuminate\Session\Store as Session;

class IlluminateErrorStore implements ErrorStoreInterface
{
    public function __construct(private readonly Session $session) {}

    public function hasError(string $key): bool
    {
        if (! $this->hasErrors()) {
            return false;
        }

        $key = $this->transformKey($key);

        return $this->getErrors()->has($key);
    }

    public function getError(string $key): ?string
    {
        if (! $this->hasError($key)) {
            return null;
        }

        $key = $this->transformKey($key);

        return $this->getErrors()->first($key);
    }

    protected function hasErrors(): bool
    {
        return $this->session->has('errors');
    }

    protected function getErrors(): mixed
    {
        return $this->hasErrors() ? $this->session->get('errors') : null;
    }

    protected function transformKey(string $key): string
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }
}
