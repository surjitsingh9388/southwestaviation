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

$partclassification = ['1'=>'Standard', '2'=>'Serialized'];
define('PARTS_CLASSIFICATION', serialize($partclassification));

$invItemType = ['1'=>'Consumable', '2'=>'Expandable', '3'=>'Rotable', '4'=>'Tool'];
define('INVENTORY_ITEM_TYPE', serialize($invItemType));

$defaultUOM = ['1'=>'Each', '2'=>'LBS', '3'=>'FT', '4'=>'IN', '5'=>'Quarts', '6'=>'Gallons'];
define('DEFAULT_UOM', serialize($defaultUOM));

$inventoryStatus = ['1'=>'Active', '11'=>'Allocated', '3'=>'Consumed', '5'=>'Damaged', '4'=>'Discarded', '14'=>'Inactive', '2'=>'Installed', '9'=>'In-Transit', '13'=>'Needs Repair', '7'=>'Out for Repair', '12'=>'Quarantine', '10'=>'Shipped', '6'=>'Unavailable', '8'=>'Unrepairable'];
define('INVENTORY_STATUS', serialize($inventoryStatus));

$currency = [1=>"USD"];
define('CURRENCY', serialize($currency));

//$invConditions = ['1'=>'Altered', '2'=>'Inspected', '3'=>'Modified', '4'=>'New', '5'=>'Other / Not Specified', '6'=>'Overhauled', '7'=>'Rebuilt', '8'=>'Repaired', '9'=>'Serviced'];
$invConditions = ['1'=>'AR', '2'=>'BER', '3'=>'FN', '4'=>'NE', '5'=>'NS', '6'=>'OH', '7'=>'RD', '8'=>'RJ', '9'=>'RP', '10'=>'RP/Core', '11'=>'SV'];
define('INVENTORY_CONDITION', serialize($invConditions));

$invLocationStatus = ['1'=>'Normal', '2'=>'Offsite', '3'=>'Quarantine'];
define('INVENTORY_LOCATION_STATUS', serialize($invLocationStatus));

$urgency = ['1'=>'AOG', '2'=>'Urgent', '3'=>'Medium', '4'=>'Low'];
define('URGENCY', serialize($urgency));

$inventoryrequeststatus = ['1'=>'Approved', '2'=>'Canceled', '4'=>'Closed', '3'=>'Denied', '0'=>'Pending Review', '5'=>'PO Created'];
define('INVENTORY_REQUEST_STATUS', serialize($inventoryrequeststatus));

$invPurchaseOrderType = ['1'=>'Standard', '2'=>'Exchange'];
define('INVENTORY_PURCHASE_TYPE', serialize($invPurchaseOrderType));

$invPurchaseOrderStatus = ['3'=>'Canceled', '4'=>'Closed', '0'=>'Draft', '1'=>'Partial Received', '2'=>'Send to Vendor'];
define('INVENTORY_PURCHASE_STATUS', serialize($invPurchaseOrderStatus));

$invPOExchangeStatus = ['1'=>'None', '2'=>'Open', '3'=>'Returned'];
define('INVENTORY_PURCHASE_EXCHANGE_STATUS', serialize($invPOExchangeStatus));

$shipVia = ['1'=>'DHL', '2'=>'FedEx Early AM', '3'=>'UPS', '4'=>'USPS'];
define('SHIP_VIA', serialize($shipVia));

$shipViaTrackURL = ['1'=>'http://www.dhl.com/en/express/tracking.html?AWB=', '2'=>'https://www.fedex.com/fedextrack/?action=track&trackingnumber=', '3'=>'https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=', '4'=>'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1='];
define('SHIP_VIA_TRACKING_URL', serialize($shipViaTrackURL));

$repairReceivedStatus = ['1'=>'Repaired', '2'=>'Replaced', '3'=>'Unrepairable'];
define('REPAIR_RECEIVED_STATUS', serialize($repairReceivedStatus));

$repairOrderStatus = ['0'=>'Draft', '1'=>'Partial Received', '2'=>'Shipped To Vendor', '3'=>'Canceled', '4'=>'Closed', '5'=>'Shipped from Vendor'];
define('REPAIR_ORDER_STATUS', serialize($repairOrderStatus));

$shippingOrderStatus = ['0'=>'Draft', '1'=>'Partial Received', '2'=>'Shipped', '3'=>'Canceled', '4'=>'Received', '5'=>'Pending Shipment'];
define('SHIPPING_ORDER_STATUS', serialize($shippingOrderStatus));

$shippingOrderDestination = ['1'=>'Internal', '2'=>'Vendor', '3'=>'Third Party'];
define('SHIPPING_ORDER_DESTINATION', serialize($shippingOrderDestination));

$transactionactionlist = [
    ['id'=>'1', 'name'=>'Activated From Equipment'],
    ['id'=>'2', 'name'=>'Adjust'],
    ['id'=>'3', 'name'=>'Allocate'],
    ['id'=>'4', 'name'=>'Cancel'],
    ['id'=>'5', 'name'=>'Capital Change'],
    ['id'=>'6', 'name'=>'Consumed'],
    ['id'=>'7', 'name'=>'Control Change'],
    ['id'=>'8', 'name'=>'Cost Adjustment'],
    ['id'=>'9', 'name'=>'Deactivated From Equipment'],
    ['id'=>'10', 'name'=>'Deallocate'],
    ['id'=>'11', 'name'=>'Dispose'],
    ['id'=>'12', 'name'=>'Division Change'],
    ['id'=>'13', 'name'=>'Error Correct'],
    ['id'=>'14', 'name'=>'Exchange Cost Adjustment'],
    ['id'=>'15', 'name'=>'Exchange Price Change'],
    ['id'=>'16', 'name'=>'Exchange Receive'],
    ['id'=>'17', 'name'=>'Import'],
    ['id'=>'18', 'name'=>'Install'],
    ['id'=>'19', 'name'=>'Install (Historical)'],
    ['id'=>'20', 'name'=>'Internal Ship'],
    ['id'=>'21', 'name'=>'Manual Entry'],
    ['id'=>'22', 'name'=>'Part Type Change'],
    ['id'=>'23', 'name'=>'Price Change'],
    ['id'=>'24', 'name'=>'Quarantined'],
    ['id'=>'25', 'name'=>'Receive'],
    ['id'=>'26', 'name'=>'Shipped'],
    ['id'=>'27', 'name'=>'Transfer'],
    ['id'=>'28', 'name'=>'Uninstall'],
    ['id'=>'29', 'name'=>'Uninstall (Historical)'],
    ['id'=>'30', 'name'=>'Unquarantined']
];

define('TRANSACTION_ACTION_LIST', serialize($transactionactionlist));

//sms send credential

define('TWILIO_ACCOUNT_SID', 'AC84a7787cd25a51be3e361e61afcc73be');
define('TWILIO_AUTH_TOKEN', '4929379927bc0ed5fe97fc99800808fa');
define('TWILIO_PHONE_NUMBER', '+18556435694');
define('OTP_MESSAGE_TEXT', 'You opt code for reset password is ');

$customerterms = array('1'=>'ACH', '2'=>'COD', '3'=>'Credit Card', '4'=>'NET 30', '5'=>'NET 45', '6'=>'NET 60', '7'=>'WIRE');
define('CUSTOMERTERMS', serialize($customerterms));

$currencyoverride = array('1'=>'Dollor USD');
define('CURRENCYOVERRIDE', serialize($currencyoverride));

$defaultPaymentMethod = array('1'=>'Credit Card', '2'=>'Cash', '3'=>'COD', '4'=>'Account');
define('DEFAULTPAYMENTMETHOD', serialize($defaultPaymentMethod));

$customerOTCClass = array('1'=>'Charter', '2'=>'Maintenance');
define('CUSTOMEROTCCLASS', serialize($customerOTCClass));

$typeOfOTCCreditCard = array('1'=>'American Express', '2'=>'Discover', '3'=>'Master Card', '4'=>'Visa');
define('TYPEOFOTCCREDITCARD', serialize($typeOfOTCCreditCard));

$defaultOTCShippingMethod = array('1'=>'FedEx 2-Day', '2'=>'FedEx Freight', '3'=>'FedEx Ground', '4'=>'FedEx Overnight P1', '5'=>'Local Delivery', '6'=>'Pick Up', '7'=>'UPS 3-day Select', '8'=>'UPS Blue', '9'=>'UPS Ground', '10'=>'UPS Red', '11'=>'UPS Server', '12'=>'USPS', '13'=>'USPS Express', '14'=>'USPS First Class', '15'=>'USPS Priority');
define('DEFAULTOTCSHIPPINGMETHOD', serialize($defaultOTCShippingMethod));

$OTCInvoiceStatus = array('1'=>'Open', '2'=>'Waiting For Parts', '3'=>'Review', '4'=>'Processed', '5'=>'Void');
define('OTCINVOICESTATUS', serialize($OTCInvoiceStatus));

$aircraftEngineType = ['1'=>'Single', '2'=>'Twin', '3'=>'Turbine', '4'=>'Jet', '5'=>'Helicopter'];
define('AIRCRAFTENGINETYPE', serialize($aircraftEngineType));

$aircraftRate = ['1'=>'Avionics', '2'=>'Mechanic'];
define('AIRCRAFTRATE', serialize($aircraftRate));

$contractRateDepartment = ['1'=>'100-Maintenance', '2'=>'200-Avionic', '3'=>'300-Paint', '4'=>'400-Interior', '5'=>'500-Outside Sale', '6'=>'600-Brazos', '7'=>'Airframe', '8'=>'Engine', '9'=>'Flights', '10'=>'SHOP'];
define('CONTRACT_RATE_DEPARTMENT', serialize($contractRateDepartment));

$aircraftWOStatus = ['1'=>'Open', '2'=>'Pending', '3'=>'Review', '4'=>'Billing', '5'=>'Tracking', '6'=>'Completed', '7'=>'Void'];
define('AIRCRAFT_WORKORDER_STATUS', serialize($aircraftWOStatus));

$aircraftWOItemStatus = ['1'=>'Open', '2'=>'Insp. Required', '3'=>'Finished', '4'=>'Rejected', '5'=>'Continued'];
define('AIRCRAFT_WORKORDER_ITEMSTATUS', serialize($aircraftWOItemStatus));

$aircraftWOCategory = ['1'=>'100-Maintenance', '2'=>'200-Avionics', '3'=>'300-Paint', '4'=>'Interior', '5'=>'Brazos', '6'=>'Airframe Recommended', '7'=>'Airworthy Airframe', '8'=>'Airworthy Appliance', '9'=>'Airworthy Engine', '10'=>'Airworthy Prop', '11'=>'Avionics Estimate', '12'=>'Avionics Recommended', '13'=>'Customer Recommended', '14'=>'Customer Rejected', '15'=>'Engine Recommended', '16'=>'GENERAL', '17'=>'Hidden Damage Inspection', '18'=>'Outside Labor', '19'=>'Pitot Static', '20'=>'RENTAL', '21'=>'REPAIR', '22'=>'SHOP', '23'=>'Shop Order', '24'=>'STOCK'];
define('AIRCRAFT_WORKORDER_CATEGORY', serialize($aircraftWOCategory));

$aircraftWOLogBookCategory = ['1'=>'Airframe', '2'=>'Avionics', '3'=>'Engine', '4'=>'Engine (And Airframe)', '5'=>'Prop', '6'=>'Prop (And Airframe)'];
define('AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY', serialize($aircraftWOLogBookCategory));

$aircraftWOWayofBilling = ['1'=>'Hourly', '2'=>'Flat', '3'=>'No Change'];
define('AIRCRAFT_WORKORDER_WAYOF_BILLING', serialize($aircraftWOWayofBilling));

$outsideRepairInfoCond = ['1'=>'1', '2'=>'18', '3'=>'9', '4'=>'AE', '5'=>'AR', '6'=>'As Removed', '7'=>'FN', '8'=>'N', '9'=>'N/E', '10'=>'NE', '11'=>'New', '12'=>'NS', '13'=>'OH', '14'=>'Overhouled', '15'=>'RD', '16'=>'RJ', '17'=>'RP', '18'=>'SV', '19'=>'VLM2-207 (C)', '20'=>'Yellow Tagged'];
define('OUTSIDE_REPAIR_INFO_CONDITION', serialize($outsideRepairInfoCond));

$aircraftComplianceType = ['1'=>'SL', '2'=>'OH', '3'=>'OC'];
define('AIRCRAFT_COMPLIANCE_TYPE', serialize($aircraftComplianceType));

$woOSRVendorClass = ['1'=>'Avionics', '2'=>'OSR', '3'=>'Parts'];
define('WO_OSR_VENDOR_CLASS', serialize($woOSRVendorClass));

$defaultShipTo = ['1'=>'Billing Address', '2'=>'Shipping Address', '3'=>'Other'];
define('DEFAULT_SHIP_TO', serialize($defaultShipTo));

//Aircrft work order osr purchase order status
$aircraftWOOSRPOStatus = ['1'=>'Open', '2'=>'Approval Needed', '3'=>'Approved', '4'=>'Ordered', '5'=>'Received', '6'=>'Processing', '7'=>'Completed', '8'=>'Return', '9'=>'Void'];
define('AIRCRAFT_WO_OSR_PO_STATUS', serialize($aircraftWOOSRPOStatus));

$aircraftWOOSRPOInitials = ['1'=>'AC', '2'=>'DCG', '3'=>'JM', '4'=>'KA', '5'=>'KC', '6'=>'LC', '7'=>'part', '8'=>'RSL'];
define('AIRCRAFT_WO_OSR_PO_INITIALS', serialize($aircraftWOOSRPOInitials));

$woPaymentMethod = array('1'=>'Cash', '2'=>'Check', '3'=>'Credit Card', '4'=>'Other');
define('WOPAYMENTMETHOD', serialize($woPaymentMethod));

$woOptionMiscChargeMethod = array('1'=>'Manual', '2'=>'% Labor');
define('WOOPTIONMISCCHARGEMETHOD', serialize($woOptionMiscChargeMethod));

$woOptionPercentageOfLabor = array('1'=>'Use Standard %', '2'=>'Use Fluctuating %', '3'=>'Use Dual Rate %');
define('WOOPTIONPERCENTAGEOFLABOR', serialize($woOptionPercentageOfLabor));

$woOptTaxMethod = ['1'=>'Standard', '2'=>'Fluctating', '3'=>'Dual Rate'];
define('WOOPTIONTAXMETHOD', serialize($woOptTaxMethod));

$billingRateMethod = ['1'=>'Technician Rate', '2'=>'Aircraft Rate'];
define('WOOPTIONBILLINGRATEMETHOD', serialize($billingRateMethod));

$woOptBillingType = ['1'=>'QuickBooks'];
define('WOOPTIONBILLINGTYPE', serialize($woOptBillingType));

$woOptAircraftMethod = ['1'=>'Avionics', '2'=>'Mechanic'];
define('WOOPTIONAIRCRAFTMETHOD', serialize($woOptAircraftMethod));

$woItemOverviewWarranty = ['1'=>'Garmin', '2'=>'Piaggio America'];
define('WOITEMOVERVIEWWARRANTY', serialize($woItemOverviewWarranty));

$woItemSignOff = ['1'=>'Final Inspection', '2'=>'Function Check', '3'=>'Function Check as applicable', '4'=>'Hidden Damage', '5'=>'Hidden Damage as applicable', '6'=>'In Process', '7'=>'In Process as applicable', '8'=>'Inspector Code', '10'=>'Prelim Inspection', '11'=>'RII', '12'=>'Technician', '13'=>'Technician 01', '14'=>'Technician 02', '15'=>'Technician 03', '16'=>'Technician 04', '17'=>'Technician 05', '18'=>'Technician 06', '19'=>'Technician 07', '20'=>'Technician 08', '21'=>'Technician 09', '22'=>'Technician 10', '23'=>'Technician 2'];
define('WOITEMSIGNOFF', serialize($woItemSignOff));

$toolCertification = ['1'=>'Certified', '2'=>'Reference Only'];
define('TOOLCERTIFICATION', serialize($toolCertification));

$calibrationStatus = ['1'=>'In Service', '2'=>'Quarantined', '3'=>'Out for Calibration'];
define('CALIBRATIONSTATUS', serialize($calibrationStatus));

$estimated_rate = '135.00';
define('ESTIMATEDRATE', $estimated_rate);

$technicianBillingStyle = ['1'=>'Standard Rate', '2'=>'Override Rate an Hour', '3'=>'Flat Technician Amount'];
define('TECHNICIANBILLINGSTYLE', serialize($technicianBillingStyle));

$repairOrderRates = ['1'=>'Maintenance'];
define('REPAIRORDERRATES', serialize($repairOrderRates));