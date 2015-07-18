<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ranges\DateTimeRange;
use \DateTime;

/**
 * Tests the DateTimeRange class.
 */
class DateTimeRangeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider rangeProvider
     */
    public function testContains(DateTime $startValue, DateTime $endValue, DateTime $givenValue, DateTime $expectedValue, $expectedResult)
    {
        $range = new DateTimeRange($startValue, $endValue);
        $result = $range->contains($givenValue);

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testClamp(DateTime $startValue, DateTime $endValue, DateTime $givenValue, DateTime $expectedValue, $expectedResult)
    {
        $range = new DateTimeRange($startValue, $endValue);
        $result = $range->clamp($givenValue);

        $this->assertEquals($givenValue, $expectedValue);
        $this->assertEquals($result, $expectedResult);
    }

    /**
     * Provides data to be looped over for testContains() and testClamp().
     */
    public function rangeProvider()
    {
        /**
         * ($startValue, $endValue, $givenValue, $expectedValue, $expectedResult)
         */
        return array(
          array(new DateTime('2000-01-01'), new DateTime('2015-07-18'), new DateTime('1979-07-02'), new DateTime('2000-01-01'), -1),
          array(new DateTime('1964-02-07'), new DateTime('1995-06-08'), new DateTime('1986-12-24'), new DateTime('1986-12-24'), 0),
          array(new DateTime('1774-07-04'), new DateTime('1969-07-20'), new DateTime('2055-01-13'), new DateTime('1969-07-20'), 1),
        );

        /**
         * Random information about the above dates!
         *
         * July 4th, 1774: An excuse to light off fireworks once a year.
         * February 7th, 1964: The Beatles land in America.
         * July 20th, 1969: Apollo 11 lands on the moon.
         * July 2nd, 1979: The day the funk died.
         * December 24th, 1986: I grace the world with my presence.
         * June 8th, 1995: PHP Version 1.0 is released.
         * January 1st, 2000: Y2K happened and we all survived.
         * July 18th, 2015: I wrote these tests.
         * January 13th, 2055: I don't know yet. Make it something great.
         */
    }
}
