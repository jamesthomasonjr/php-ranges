<?php

namespace Ranges;

use Traits\ValidatedSettings;
use \DateTime;

/**
 * An immutable representation of a Date Time range.
 *
 * An immutable representation of a Date Time range which provides several
 * helper methods for dealing with range-based logic.
 */
class DateTimeRange
{
    /**
     * The beginning of the range.
     *
     * @var DateTime
     */
    private $start;

    /**
     * The end of the range.
     *
     * @var DateTime
     */
    private $end;

    /**
     * Creates a DateTimeRange object.
     *
     * Creates an immutable object representing a DateTime range.
     *
     * @param DateTime $start
     * @param DateTime $end
     * @param array|null $settings
     *
     * @return DateTimeRange $this
     */
    public function __construct(DateTime $start, DateTime $end, $settings = [])
    {
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
     * @param DateTime $start
     *
     * @return DateTimeRange $this
     */
    private function setStart(DateTime $start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Sets the end of the range.
     *
     * @param DateTime $end
     *
     * @return DateTimeRange $this
     */
    private function setEnd(DateTime $end)
    {
        $this->end = $end;

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
     * @param DateTime $value
     *
     * @return int $contained
     */
    public function contains($value)
    {
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
     * @param DateTime $value
     *
     * @return int $contained
     */
    public function clamp(DateTime &$value)
    {
        $contained = $this->contains($value);

        // Since the start and end of the range are stored as the asci codes,
        // we use chr() in order to get back the corresponding character.
        if ($contained < 0) {
            $value = $this->start;
        } elseif ($contained > 0) {
            $value = $this->end;
        }

        return $contained;
    }
}

