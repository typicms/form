<?php

namespace TypiCMS\Form\Tests;

use Illuminate\Session\Store;
use Illuminate\Support\MessageBag;
use Mockery;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\ErrorStore\IlluminateErrorStore;

/**
 * @internal
 *
 * @coversNothing
 */
class IlluminateErrorStoreTest extends TestCase
{
    public function test_it_converts_array_keys_to_dot_notation(): void
    {
        $errors = new MessageBag(['foo.bar' => 'Some error']);
        $session = Mockery::mock(Store::class);
        $session->shouldReceive('has')->with('errors')->andReturn(true);
        $session->shouldReceive('get')->with('errors')->andReturn($errors);

        $errorStore = new IlluminateErrorStore($session);
        $this->assertTrue($errorStore->hasError('foo[bar]'));
    }
}
