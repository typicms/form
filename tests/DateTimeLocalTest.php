<?php

namespace TypiCMS\Form\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\DateTimeLocal;

/**
 * @internal
 *
 * @coversNothing
 */
class DateTimeLocalTest extends TestCase
{
    use InputContractTest;

    protected function newTestSubjectInstance($name): DateTimeLocal
    {
        return new DateTimeLocal($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'datetime-local';
    }

    public function test_date_time_values_are_bound_as_formatted_strings(): void
    {
        $dateTimeLocal = new DateTimeLocal('dob');
        $dateTimeLocal->value(new DateTime('12-04-1988 10:33'));

        $expected = '<input type="datetime-local" name="dob" value="1988-04-12T10:33">';
        $this->assertSame($expected, $dateTimeLocal->render());
    }

    public function test_date_time_default_values_are_bound_as_formatted_strings(): void
    {
        $dateTimeLocal = new DateTimeLocal('dob');
        $dateTimeLocal->defaultValue(new DateTime('12-04-1988 10:33'));

        $expected = '<input type="datetime-local" name="dob" value="1988-04-12T10:33">';
        $this->assertSame($expected, $dateTimeLocal->render());
    }
}
