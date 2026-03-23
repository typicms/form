<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Password;

/**
 * @internal
 *
 * @coversNothing
 */
class PasswordTest extends TestCase
{
    use TextSubclassContractTest;

    protected function newTestSubjectInstance($name): Password
    {
        return new Password($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'password';
    }
}
