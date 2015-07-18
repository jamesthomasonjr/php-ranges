<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ranges\AlphabeticalRange;

/**
 * Tests the AlphabeticalRange class.
 */
class AlphabeticalRangeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider rangeProvider
     */
    public function testContains($startValue, $endValue, $givenValue, $expectedValue, $expectedResult)
    {
        $range = new AlphabeticalRange($startValue, $endValue);
        $result = $range->contains($givenValue);

        $this->assertEquals($result, $expectedResult);
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testClamp($startValue, $endValue, $givenValue, $expectedValue, $expectedResult)
    {
        $range = new AlphabeticalRange($startValue, $endValue);
        $result = $range->clamp($givenValue);

        $this->assertEquals($givenValue, $expectedValue);
        $this->assertEquals($result, $expectedResult);
    }

    public function rangeProvider()
    {
        /**
         * ($startValue, $endValue, $givenValue, $expectedValue, $expectedResult)
         */
        return array(
          array('G', 'U', 'A', 'G', -1),
          array('F', 'H', 'G', 'G', 0),
          array('D', 'P', 'X', 'P', 1),
        );
    }
}
