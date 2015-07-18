<?php

namespace Ranges\Traits;

trait ValidatedSettings
{
    protected $settings;
    protected static $possible = [];
    protected static $default = [];

    protected function setSettings(array $settings)
    {
        $this->settings = $this->validateSettings($settings);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    protected function validateSettings(array $settings)
    {
        // Define possible setting values.
        $possible = static::$possible;

        // Define default setting values.
        $default = static::$default;

        // Trim provided settings to only valid setting keys.
        $matched = array_intersect_key($settings, $possible);

        // Trim resulting settings to only valid setting values.
        $valid = array_filter(
            $matched,
            [$this, 'settingsValueIsValid'],
            ARRAY_FILTER_USE_BOTH
        );

        // Use default settings for any settings that were either not provided or invalid.
        $result = array_merge($valid, $default);

        return $result;
    }

    protected function settingsValueIsValid($key, $value)
    {
        // Get the valid values for the given settings key
        $validValues = static::$possible[$key];

        // Check if the provided settings value is valid
        return in_array($value, $validValues);
    }
}

