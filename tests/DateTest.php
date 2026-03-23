<?php

namespace TypiCMS\Form\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Date;

/**
 * @internal
 *
 * @coversNothing
 */
class DateTest extends TestCase
{
    use InputContractTest;

    protected function newTestSubjectInstance($name): Date
    {
        return new Date($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'date';
    }

    public function test_date_time_values_are_bound_as_formatted_strings(): void
    {
        $date = new Date('dob');
        $date->defaultValue(new DateTime('12-04-1988 10:33'));

        $expected = '<input type="date" name="dob" value="1988-04-12">';
        $this->assertSame($expected, $date->render());
    }

    public function test_date_time_default_values_are_bound_as_formatted_strings(): void
    {
        $date = new Date('dob');
        $date->defaultValue(new DateTime('12-04-1988 10:33'));

        $expected = '<input type="date" name="dob" value="1988-04-12">';
        $this->assertSame($expected, $date->render());
    }
}
