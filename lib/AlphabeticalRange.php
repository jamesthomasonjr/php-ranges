<?php

namespace Ranges;

use Traits\ValidatedSettings;

/**
 * An immutable representation of an alphabetical range.
 *
 * An immutable representation of an alphabetical range which provides several
 * helper methods for dealing with range-based logic.
 */
class AlphabeticalRange
{
    /**
     * Trait which adds a settings property and validation + default capabilities
     */
    use ValidatedSettings;

    /**
     * The beginning of the range.
     *
     * @var string
     */
    private $start;

    /**
     * The end of the range.
     *
     * @var string
     */
    private $end;

    /**
     * Creates an AlphabeticalRange object.
     *
     * Creates an immutable object representing a alphabetical range.
     *
     * @param string $start
     * @param string $end
     * @param array|null $settings
     *
     * @return AlphabeticalRange $this
     */
    public function __construct($start, $end, $settings = [])
    {
        $this->setSettings($settings);

        /**
         * ASCII Codes are easier to work with and using ord() automagically
         * grabs just the first provided character from a string.
         *
         * However, this means that the entire set of the range A-Z comes before
         * the entire set of the range a-z.
         */
        $startASCIICode = ord($start);
        $endASCIICode = ord($end);

        if ($startASCIICode <= $endASCIICode) {
            $this->setStart($start);
            $this->seEnd($end);
        } else {
            $this->setStart($end);
            $this->setEnd($start);
        };
    }

    /**
     * Sets the beginning of the range.
     *
     * @param string $start
     *
     * @return AlphabeticalRange $this
     */
    private function setStart($start)
    {
        $this->validateValue($start);
        $this->start = ord($start);

        return $this;
    }

    /**
     * Sets the end of the range.
     *
     * @param string $end
     *
     * @return AlphabeticalRange $this
     */
    private function setEnd($end)
    {
        $this->validateValue($end);
        $this->end = ord($end);

        return $this;
    }

    /**
     * Returns whether the given character is contained within the range
     *
     * Returns whether the given character is contained within the range, is
     * before the range, or is after the range. The result will be -1 if the
     * value is before the range, 0 if the value is within the range, or +1 if
     * the value is after the range.
     *
     * @param string $value
     *
     * @return int $contained
     */
    public function contains($value)
    {
        $this->validateValue($value);
        $contained = 0;

        $code = ord($value);

        if ($code < $this->start) {
            $contained--;
        } elseif ($code > $this->end) {
            $contained++;
        }

        return $contained;
    }

    /**
     * Clamps a given value to fit within the range.
     *
     * Modifies a value (passed by reference) so that it fits within the range.
     * Like contains(), this function returns -1, 0, or +1. If the object was
     * already within range, then a 0 is returned. If the object was below the
     * range, then -1 is returned. If the object was beyond the range, then +1
     * is returned.
     *
     * @param string $value
     *
     * @return int $contained
     */
    public function clamp(&$value)
    {
        $this->validateValue($value);
        $contained = $this->contains($value);

        // Since the start and end of the range are stored as the asci codes,
        // we use chr() in order to get back the corresponding character.
        if ($contained < 0) {
            $value = chr($this->start);
        } elseif ($contained > 0) {
            $value = chr($this->end);
        }

        return $contained;
    }

    private function validateValue($value)
    {
        if(!is_string($value)) {
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
            throw new \InvalidArgumentException($caller . " expects a(n) " . $type . " as an argument!");
        }
    }
}

