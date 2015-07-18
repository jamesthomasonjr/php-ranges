<?php

namespace Ranges;

use Ranges\Traits\ValidatedSettings;

/**
 * An immutable representation of a numerical range.
 *
 * An immutable representation of a numerical range which provides several
 * helper methods for dealing with range-based logic.
 */
class NumericalRange
{
    /**
     * Trait which adds a settings property and validation + default capabilities
     */
    use ValidatedSettings;

    /**
     * The beginning of the range.
     *
     * @var float|int
     */
    private $start;

    /**
     * The end of the range.
     *
     * @var float|int
     */
    private $end;

    /**
     * The accepted settings values.
     *
     * @return array[]
     */
    protected static function getPossibleSettings()
    {
        static $possible = [
            "type" => ["int", "float", "numeric"],
        ];

        return $possible;
    }

    /**
     * The default settings values.
     *
     * @return string[]
     */
    protected static function getDefaultSettings()
    {
        static $default = [
            "type" => "numeric",
        ];

        return $default;
    }

    /**
     * Creates a NumericalRange object.
     *
     * Creates an immutable object representing a numerical range.
     *
     * @param float|int $start
     * @param float|int $end
     * @param array|null $settings
     *
     * @return NumericalRange $this
     */
    public function __construct($start, $end, array $settings = [])
    {
        $this->setSettings($settings);

        if ($start <= $end) {
            $this->setStart($start);
            $this->setEnd($end);
        } else {
            $this->setStart($end);
            $this->setEnd($start);
        };
    }

    /**
     * Sets the beginning of the range.
     *
     * @param float|int $start
     *
     * @return NumericalRange $this
     */
    private function setStart($start)
    {
        $this->validateValue($start);
        $this->start = $start;

        return $this;
    }

    /**
     * Sets the end of the range.
     *
     * @param float|int $end
     *
     * @return NumericalRange $this
     */
    private function setEnd($end)
    {
        $this->validateValue($end);
        $this->end = $end;

        return $this;
    }

    /**
     * Returns whether the given number is contained within the range
     *
     * Returns whether the given number is contained within the range, is
     * before the range, or is after the range. The result will be -1 if the
     * value is before the range, 0 if the value is within the range, or +1 if
     * the value is after the range.
     *
     * @param float|int $value
     *
     * @return int $contained
     */
    public function contains($value)
    {
        $this->validateValue($value);
        $contained = 0;

        if ($value < $this->start) {
            $contained--;
        } elseif ($value > $this->end) {
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
     * @param float|int $value
     *
     * @return int $contained
     */
    public function clamp(&$value)
    {
        $this->validateValue($value);
        $contained = $this->contains($value);

        if ($contained < 0) {
            $value = $this->start;
        } elseif ($contained > 0) {
            $value = $this->end;
        }

        return $contained;
    }

    private function validateValue($value)
    {
        // $type should only ever be int, float, or numeric
        $type = $this->settings['type'];
        $validate = "is_" . $type;

        if(!$validate($value)) {
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
            throw new \InvalidArgumentException($caller . " expects a(n) " . $type . " as an argument!");
        }
    }
}

