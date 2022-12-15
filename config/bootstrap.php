<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . '/paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.default to `config/.env` and set/modify the
 * variables as required.
 */
 if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
     $dotenv->parse()
         ->putenv()
         ->toEnv()
         ->toServer();
 }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file.
 * You can use a file like app_local.php to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local', 'default');

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    //Configure::write('Cache._cake_model_.duration', '+2 minutes');
    //Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
$isCli = PHP_SAPI === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

/*
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
Email::setConfigTransport(Configure::consume('EmailTransport'));
Email::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
//Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/*
 * Setup detectors for mobile and tablet.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * Enable immutable time objects in the ORM.
 *
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/3.0/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
Type::build('time')
    ->useImmutable();
Type::build('date')
    ->useImmutable();
Type::build('datetime')
    ->useImmutable();
Type::build('timestamp')
    ->useImmutable();

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);
//Inflector::rules('transliteration', ['/å/' => 'aa']);

/*
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

/*
 * Only try to load DebugKit in development mode
 * Debug Kit should not be installed on a production system
 */
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

Plugin::load('Dompdf');

/*
* Softdelete plugin for archive
*
*/
Plugin::load('SoftDelete');

/*Constants used in application*/
define("ADMIN_EMAIL", env('ADMIN_EMAIL', null));

/*Constants used in welcome user email*/
define("ADMIN_SENDER_EMAIL", env('ADMIN_SENDER_EMAIL', null));
define("ADMIN_SENDER_NAME", env('ADMIN_SENDER_NAME', null));
define("ADMIN_SENDER_PHONE", env('ADMIN_SENDER_PHONE', null));
define("ADMIN_SENDER_ADDRESS", env('ADMIN_SENDER_ADDRESS', null));
// set variable for subscription renew alert time
define('TICKET_NO', 150000);
define('INVOICE_NO', 8000000);

define('PAGINATION_LIMIT', 25);
define('FORGOT_PASSWORD_EMAIL_SEND_SUCCESSFULLY', 'You password reset details successfully sent to your email address, if you do not see it, please check your junk mail or contact '. env('ADMIN_SENDER_EMAIL').'.');

#Role Names
define("ROLE_ADMIN", env('ROLE_ADMIN'));
#define("ROLE_CORPORATE_ADMIN", env('ROLE_CORPORATE_ADMIN'));
#define("ROLE_CORPORATE_USER", env('ROLE_CORPORATE_USER'));
#define("ROLE_INDIVIDUAL", env('ROLE_INDIVIDUAL'));

define("SUBMITING", env('SUBMITING'));

define('UNAUTHORIZED_ADMIN_USER', 'You need Admin Role to access the Admin Portal, please call us at +1-918-298-3718 or write to us at '. env('ADMIN_SENDER_EMAIL').'.');
define('UNAUTHORIZED_PILOT_USER', 'You need Pilot Role to access the Pilot Portal, please call us at +1-918-298-3718 or write to us at '. env('ADMIN_SENDER_EMAIL').'.');

//bcc email list for all outgoing emails
define('BCC_LIST_FOR_ALL_OUTGOING_MAILS', env('BCC_LIST_FOR_ALL_OUTGOING_MAILS'));

//Page size for US
define('PAGE_SIZE', env('PAGE_SIZE'));

define('RESTRICTED_EVONTECH_ADMIN_FUNCTIONALITIES', env('RESTRICTED_EVONTECH_ADMIN_FUNCTIONALITIES'));
define('EVONTECH_ADMIN_EMAIL', env('EVONTECH_ADMIN_EMAIL'));
define('RESTRICTION_MESSAGE', env('RESTRICTION_MESSAGE'));

define('APPLICATION_DEPLOYED_ON_SERVER', env('APPLICATION_DEPLOYED_ON_SERVER'));
define('BCC_LIST_FOR_USER_WELCOME_EMAIL', env('BCC_LIST_FOR_USER_WELCOME_EMAIL'));

define('ROLE_PILOTS', env('ROLE_PILOTS'));
define('PILOTS_PREFIX', env('PILOTS_PREFIX'));
define('PILOTS_ID', env('PILOTS_ID'));

//Role ID to give permissions
define('PERMISSION_ROLE_ID', env('PERMISSION_ROLE_ID'));
define('PILOT_ROLE_ID', env('PILOT_ROLE_ID'));

#Full permission access User ID
define('FULL_PERMISSION_ACCESS_ID', env('FULL_PERMISSION_ACCESS_ID'));
#Captcha site key
define('CAPTCHA_SECRET_KEY', env('CAPTCHA_SECRET_KEY'));
define('CAPTCHA_SITE_KEY', env('CAPTCHA_SITE_KEY'));

define('CC_LIST_FOR_USER_WELCOME_EMAIL', env('CC_LIST_FOR_USER_WELCOME_EMAIL'));
define('CC_LIST_FOR_UPDATE_BOOKING_EMAIL', env('CC_LIST_FOR_UPDATE_BOOKING_EMAIL'));
define('ROLE_PERMISSION_CONFIRM_MESSAGE', env('ROLE_PERMISSION_CONFIRM_MESSAGE'));