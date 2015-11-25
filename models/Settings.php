<?php namespace BYTEPOETS\BasicAuth\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'bytepoets_basicauth_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}