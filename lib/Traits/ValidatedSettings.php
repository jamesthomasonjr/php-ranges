<?php

namespace Ranges\Traits;

trait ValidatedSettings
{
    protected $settings;
    protected abstract static function getPossibleSettings();
    protected abstract static function getDefaultSettings();

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
        $possible = static::getPossibleSettings();

        // Define default setting values.
        $default = static::getDefaultSettings();

        // Trim provided settings to only valid setting keys.
        $matched = array_intersect_key($settings, $possible);

        // Trim resulting settings to only valid setting values.
        $valid = array_filter(
            $matched,
            [$this, 'settingsValueIsValid']
        );

        // Use default settings for any settings that were either not provided or invalid.
        $result = array_merge($valid, $default);

        return $result;
    }

    protected function settingsValueIsValid($key, $value)
    {
        // Get the valid values for the given settings key
        $validValues = static::getPossibleSettings()[$key];

        // Check if the provided settings value is valid
        return in_array($value, $validValues);
    }
}

