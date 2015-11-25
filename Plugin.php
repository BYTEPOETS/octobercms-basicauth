<?php namespace BYTEPOETS\BasicAuth;

use System\Classes\PluginBase;
use Config;
use Session;
use Request;
use Response;
use Backend;
use BYTEPOETS\BasicAuth\Models\Settings;

/**
 * BasicAuth Plugin Information File
 */
class Plugin extends PluginBase
{
    const SESSION_KEY = 'basic-auth-authenticated-as';

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'BasicAuth',
            'description' => 'It protects the whole CMS with a Basic Auth form stored in session.',
            'author'      => 'BYTEPOETS',
            'icon'        => 'icon-lock'
        ];
    }

    public function boot()
    {
        $isBasicAuthEnabled = Config::get('app.debug') || $this->app->environment() === 'staging' || $this->app->environment() === 'production';
        $isTesting = $this->app->environment() === 'testing';
        $isAuthenticated = !!strlen(Session::get(self::SESSION_KEY));
        $username = Settings::instance()->username ?: Config::get('bytepoets.basicauth::username', false);
        $password = Settings::instance()->password ?: Config::get('bytepoets.basicauth::password', false);

        if (!$isTesting && !$isAuthenticated && $isBasicAuthEnabled) {
            if (Request::getUser() === $username && Request::getPassword() === $password) {
                Session::set(self::SESSION_KEY, Request::getUser());
            }
            else {
                header('WWW-Authenticate: Basic');
                header('HTTP/1.0 401 Unauthorized');
                echo 'Unauthorized';
                exit;
            }
        }
    }

    public function registerSettings()
    {
        return [
            'location' => [
                'label'       => 'Basic Auth',
                'description' => 'Set the Username/Password for your Basic Auth Protection.',
                'icon'        => 'icon-lock',
                'order'       => 500,
                'keywords'    => 'auth password protection',
                'class'       => 'BYTEPOETS\BasicAuth\Models\Settings'
            ]
        ];
    }

}
